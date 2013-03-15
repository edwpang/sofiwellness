<?php
/*
 * Created on Sep 14, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
require_once APP_COMMON_DIR .'/Db/DbAccessor.php';
require_once 'UserMgmtDbTableDefs.php';
require_once 'UserInfo.php';
 
class UserMgmtDbAccessor extends DbAccessor
{
	const USER_TABLE = 'users_table';
	
	public function _construct ()
	{
		
	}
	
	public function getUserId ($userName)
	{
		//Log::debug ('UserMgmtDbAccessor::getUserId: userName=' .$userName);
		$db = $this->getLoginDatabase();
		$user_id = null;
		try
		{
			$db->getProfiler()->setEnabled(true);
			$tableName = UserMgmtDbTableDefs::TABLE_LOGIN;
			//Log::debug ('table name:' .$tableName);
			$rowset = $db->fetchAssoc( "SELECT user_id FROM " .$tableName ." WHERE user_name = :userName",    
				array('userName' => $userName));	
			//Log::debug ('****');
			$query =$db->getProfiler()->getLastQueryProfile();
			//Log::debug( $query->getQuery());
				
			foreach ($rowset as $row) 
			{ 
  				$user_id = $row[USER_ID];
			}					
		}	
		catch (Exception $e)
		{
			Log::error ($e->getMessage());
			$this->setErrorMessage($e->getMessage());
		}
		
		return $user_id;
	}
	
	public function getUserInfoById ($userId)
	{
		//Log::debug ('UserMgmtDbAccessor::getUserInfoById: userId='.$userId);
		$db = $this->getLoginDatabase();
		$info = new UserInfo();
		try
		{
			$loginInfo = $this->getUserLoginInfo ($userId, $db);
			$info->setLoginInfo ($loginInfo);
			$profile = $this->getUserProfileInfoByUserId ($userId, $db);
			$info->setProfileInfo ($profile);
		}	
		catch (Exception $e)
		{
			Log::debug ($e->getMessage());
			echo $e->getMessage();
		}
		
		return $info;		
	}
	

	//return ArrayList contains UserInfo
	public function getUserInfoByEmail ($email)
	{
		//Log::debug ('UserMgmtDbAccessor::getUserInfoByEmail: email='.$email);
		$db = $this->getLoginDatabase();
		$list = new ArrayList ();
		try
		{
			$profileList = $this->getUserProfileInfoByEmail ($email, $db);
			if ($profileList != null && $profileList->size() > 0)
			{
				$count = $profileList->size();
				for ($i = 0; $i < $count; $i++)
				{
					$info = new UserInfo();
					$profile = $profileList->get($i);
					if ($profile != null && $profile->getUserId () != null)
					{
						$loginInfo = $this->getUserLoginInfo ($profile->getUserId (), $db);
						$info->setLoginInfo ($loginInfo);
					}
					$info->setProfileInfo ($profile);
					$list->add ($info);
				}
			}
		}	
		catch (Exception $e)
		{
			Log::debug ($e->getMessage());
			echo $e->getMessage();
		}
		
		return $list;		
	}
	
	//if userName exists, return true, else return false
	public function userNameExist ($userName)
	{
		$exists = false;
		$userId = $this->getUserId ($userName);
		if ($userId != null && $userId > 0)
			$exists = true;
		
		return $exists;		
	}
	
	//check if the user name has been taken or not, if yes, return true, otherwise return false;
	//Note: if the user name taken by guest, but has logout or visit more than one day, then is OK to take
	public function checkUserNameExist ($userName)
	{
		//Log::debug ('UserMgmtDbAccessor::checkUserNameExist');
		$info = $this->getUserInfo ($userName);
		$userId = $info->getUserId();
		$type = $info->getAccountType();
		if ($userId == null || $userId == 0)
			return false;
		//Log::debug ('userId=' .$userId .', type=' .$type);
		if ($type != AccountTypes::GUEST)
			return true;
			
		$loginInfo = $info->getLoginInfo();
		$lastVisitTime = $loginInfo->getLastVisitTime();
		if ($lastVisitTime == null)
			$lastVisitTime = $loginInfo->getCreateTime();
		$lastLogout = $loginInfo->getLastLogoutTime();
		$curTime = DateTimeUtil::getCurrentTimeStr();
		if ($lastLogout != null && DateTimeUtil::compare ($lastLogout, $lastVisitTime) >= 0)
		{
			return false;
		}
			
		$curTimeArray = DateTimeUtil::parseDatetime($curTime);
		$vTimeArray = DateTimeUtil::parseDatetime($lastVisitTime);
		if ($curTimeArray['year'] > $vTimeArray['year'] ||
			$curTimeArray['month'] > $vTimeArray['month'] ||
			$curTimeArray['day'] > $vTimeArray['day'])
				return false;
				
		$dHour = $curTimeArray['hour'] - $vTimeArray['hour'];
		if ($dHour >= 1)
			return false;
		else
			return true;
	}

	public function getUserInfo ($userName)
	{
		$db = $this->getLoginDatabase();
		$info = new UserInfo();
		try
		{
			$loginInfo = $this->getUserLoginInfoByUserName ($userName, $db);
			if ($loginInfo != null)
			{
				$info->setLoginInfo ($loginInfo);
				$userId = $loginInfo->getId ();
				$profile = $this->getUserProfileInfoByUserId ($userId, $db);
				$info->setProfileInfo ($profile);			
			}
		}	
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
		
		return $info;	
	}
	
	//return Map contains userId = userName, input is ArrayList
	public function getUserNamesByUserIds ($listIds, $db=null)
	{
		if ($db == null)
			$db = $this->getLoginDatabase();
			
		$map = new Map();	
		try
		{
			$table = UserMgmtDbTableDefs::TABLE_LOGIN;
			$sql = 'SELECT user_id, user_name FROM ' .$table .' WHERE user_id in (';
			
			$count = $listIds->size();
			for ($i = 0; $i < $count; $i++)
			{
				if ($i > 0)
					$sql .= ",";
				$sql .= $listIds->get($i);		
			}
			
			$sql .= ")";
			//Log::debug ('SQL:' .$sql);
			$result = $db->query($sql);		
			$rowset = $result->fetchAll();	
			foreach ($rowset as $row) 
			{ 
				$map->set ($row['user_id'], $row['user_name']);
			}					
		}
		catch (Exception $e)
		{
			Log::error ('getUserNamesByUserIds error:' .$e->getMessage());
			$this->setErrorMessage ($e->getMessage());
		}
		
		return $map;				
		
	}
	
	public function saveUserInfo ($userInfo)
	{
		//Log::debug ('UserMgmtDbAccessor::saveUserInfo');
		$db = $this->getLoginDatabase();
		$inTransaction = false;
		try 
		{  	
			//check if user already exists
			$userId = $userInfo->getUserId();
			if ($userId == null)
				$userId = $this->getUserId ($userInfo->getUserName());
				
			if ($userId != null && $userId > 0)
			{
				$userInfo->setUserId ($userId);
				$profile = $userInfo->getProfileInfo();
				if ($profile->getId() == null)
				{
					$pf = $this->getUserProfileInfoByUserId($userId, $db);
					if ($pf != null)
						$profile->setId ($pf->getId());
				}
				
				//Log::debug ("userId:" .$userId .', account type:' .$userInfo->getAccountType() .', profileId:' .$profile->getId());
				
				//if all OK, just update
				if ($profile->getId() != null)
					$this->updateUserInfo($userInfo);
				else
				{
					$this->updateUserLoginInfo($userInfo->getLoginInfo(), $db);
					$profile->setUserId ($userId);
					$this->saveUserProfileInfo ($profile, $db);
				}
			}
			else
			{
				$db->beginTransaction();
				$inTransaction = true;
				$logInfo = $this->saveUserLoginInfo ($userInfo->getLoginInfo(), $db);
				$profile = $userInfo->getProfileInfo();
				if ($profile != null)
				{
					$profile->setUserId ($logInfo->getId());
					$profile = $this->saveUserProfileInfo ($profile, $db);
				}
				$db->commit();
			}
		} 
		catch (Exception $e) 
		{    		
			Log::debug ('save user info error:' .$e->getMessage());	
			if ($inTransaction)
				$db->rollBack();	 
		}	
		
		return $userInfo;				
	}
	
	public function updateUserInfo ($userInfo, $updateProfile=true)
	{
		//Log::debug ('UserMgmtDbAccessor::updateuserInfo');
		$db = $this->getLoginDatabase();
			
		try 
		{  	
			$db->beginTransaction();
			$this->updateUserLoginInfo ($userInfo->getLoginInfo(), $db);
			if ($updateProfile)
				$this->updateUserProfileInfo ($userInfo->getProfileInfo(), $db);
			$db->commit();
		} 
		catch (Exception $e) 
		{    		
			Log::debug ('save user info error:' .$e->getMessage());		 
			$this->setErrorMessage ($e->getMessage());
			$db->rollBack();
		}	
		
		return $userInfo;				
	}
	
	/////// for user login info //////////////////////////////
	
	public function getUserLoginInfo ($userId, $db = null)
	{
		//Log::debug ('getUserLoginInfo');
		if ($db == null)
			$db = $this->getLoginDatabase();
			
		$info = null;	
		try
		{
			$table = UserMgmtDbTableDefs::TABLE_LOGIN;
			$sql = 'SELECT * FROM ' .$table .' WHERE user_id='.$userId;
			//Log::debug ('SQL:' .$sql);
			$result = $db->query($sql);		
			$rowset = $result->fetchAll();	
			foreach ($rowset as $row) 
			{ 
				$info = new LoginInfo();
				$this->populateUserLoginInfo ($row, $info);
				break; //should be only one
			}					
		}
		catch (Exception $e)
		{
			Log::error ('getUserLoginInfo error:' .$e->getMessage());
			$this->setErrorMessage ($e->getMessage());
		}
		
		return $info;		
	}

	public function getUserLoginInfoByUserName ($userName, $db = null)
	{
		if ($db == null)
			$db = $this->getLoginDatabase();
			
		$info = null;	
		try
		{
			$table = UserMgmtDbTableDefs::TABLE_LOGIN;
			$sql = 'SELECT * FROM ' .$table .' WHERE user_name=\''.$userName .'\'';
			$result = $db->query($sql);		
			$rowset = $result->fetchAll();	
			foreach ($rowset as $row) 
			{ 
				$info = new LoginInfo();
				$this->populateUserLoginInfo ($row, $info);
				break; //should be only one
			}					
		}
		catch (Exception $e)
		{
			Log::error ('getUserLoginInfo error:' .$e->getMessage());
			$this->setErrorMessage ($e->getMessage());
		}
		
		return $info;	
	}
	
	public function saveUserLoginInfo ($info, $db = null)
	{
		if ($db == null)
			$db = $this->getLoginDatabase();
			
		try
		{
			$table = UserMgmtDbTableDefs::TABLE_LOGIN;
			$params = $this->binUserLoginInfoParams ($info);
			$db->insert($table, $params);
			$last_insert_id = $db->lastInsertId();	
			$info->setId ($last_insert_id);
		}
		catch (Exception $e)
		{
			Log::error ('saveUserLoginInfo error:' .$e->getMessage());
			$this->setErrorMessage ('Save login info error:' .$e->getMessage());
		}
		
		return $info;
	}
	
	public function updateUserLoginInfo ($info, $db=null)
	{
		//Log::debug ('UserMgmtDbAccessor::updateUserLoginInfo');
		if ($db == null)
			$db = $this->getLoginDatabase();
			
		try 
		{  	
			$db->getProfiler()->setEnabled(true);
			$table = UserMgmtDbTableDefs::TABLE_LOGIN;
			$params = new Map();
			if ($info->getPassword() != null)
				$params->set(UserMgmtDbTableDefs::PASSWORD, $info->getPassword());
			$params->set(UserMgmtDbTableDefs::QUESTION, $info->getQuestion());
			$params->set(UserMgmtDbTableDefs::ANSWER, $info->getAnswer());
			if ($info->getAccountType() != AccountTypes::NONE)
				$params->set(UserMgmtDbTableDefs::ACCOUNT_TYPE, $info->getAccountType());
			
			//$params = $this->binUserLoginInfoParams ($info);
			$where = $db->quoteInto('user_id = ?', $info->getId());
			$rows_affected = $db->update($table, $params->getAll(), $where);
		    $query =$db->getProfiler()->getLastQueryProfile();
			Log::debug( $query->getQuery());
		} 
		catch (Exception $e) 
		{    		
			Log::error ('update login info error:' .$e->getMessage());		 
			$this->setErrorMessage ('Update login info erro:' .$e->getMessage());
		}	
		
		return $info;				
	}	 
	
	public function updatePassword ($userId, $password)
	{
		//Log::debug ('updatePassword');
		$db = $this->getLoginDatabase();
			
		$bOK = false;
		try 
		{  	
			$table = UserMgmtDbTableDefs::TABLE_LOGIN;
			$sql = "UPDATE " .$table ." SET " .UserMgmtDbTableDefs::PASSWORD ."='" .$password ."'";
			$sql .= ' WHERE user_id=' .$userId;
			$result = $db->query($sql);
			$bOK = true;
		} 
		catch (Exception $e) 
		{    		
			Log::error ('update Password error:' .$e->getMessage());		 
			$this->setErrorMessage ('updatePassword erro:' .$e->getMessage());
		}	
		
		return $bOK;		
	}
	
	public function updateUserLoginStatus ($userId, $status)
	{
		$db = $this->getLoginDatabase();
			
		$bOK = false;
		try 
		{  	
			$table = UserMgmtDbTableDefs::TABLE_LOGIN;
			$sql = 'UPDATE ' .$table .' SET ' .UserMgmtDbTableDefs::STATUS_CODE .'=' .$status;
			$sql .= ' WHERE user_id=' .$userId;
			$result = $db->query($sql);
			$bOK = true;
		} 
		catch (Exception $e) 
		{    		
			Log::error ('updateUserLoginStatus error:' .$e->getMessage());		 
			$this->setErrorMessage ('Update status erro:' .$e->getMessage());
		}	
		
		return $bOK;
	}
	
	public function updateLastVisitTime ($userId, $lastVisitTime)
	{
		$db = $this->getLoginDatabase();
			
		$bOK = false;
		try 
		{  	
			$table = UserMgmtDbTableDefs::TABLE_LOGIN;
			$params = array (UserMgmtDbTableDefs::LAST_VISIT_TIME => $lastVisitTime);
			$where = $db->quoteInto('user_id = ?', $userId);
			$rows_affected = $db->update($table, $params, $where);
			$bOK = true;
		} 
		catch (Exception $e) 
		{    		
			Log::error ('updateLastVisitTime error:' .$e->getMessage());		 
			$this->setErrorMessage ('Update last visit time erro:' .$e->getMessage());
		}	
		
		return $bOK;
	}	
	
	
	public function updateLastLogoutTime ($userId, $time)
	{
		$db = $this->getLoginDatabase();
			
		$bOK = false;
		try 
		{  	
			$table = UserMgmtDbTableDefs::TABLE_LOGIN;
			$params = array (UserMgmtDbTableDefs::LAST_LOGOUT_TIME => $time);
			$where = $db->quoteInto('user_id = ?', $userId);
			$rows_affected = $db->update($table, $params, $where);
			$bOK = true;
		} 
		catch (Exception $e) 
		{    		
			Log::error ('updateLastLogoutTime error:' .$e->getMessage());		 
			$this->setErrorMessage ('Update last logout time erro:' .$e->getMessage());
		}	
		
		return $bOK;
	}		
	
	////////// for user profile info ///////////////////////////
	public function getUserProfileInfoByUserId ($userId, $db = null)
	{
		//Log::debug ('UserMgmtDbAccessor::getUserProfileInfoByUserId');
		if ($db == null)
			$db = $this->getUserProfileDatabase();
			
		$info = null;	
		try
		{
			$table = UserMgmtDbTableDefs::TABLE_PROFILE;
			$sql = 'SELECT * FROM ' .$table .' WHERE user_id='.$userId;
			$result = $db->query($sql);		
			$rowset = $result->fetchAll();	
			foreach ($rowset as $row) 
			{ 
				$info = new UserProfileInfo();
				$this->populateUserProfileInfo ($row, $info);
				break; //should be only one
			}					
		}
		catch (Exception $e)
		{
			Log::error ('getUserLoginInfo error:' .$e->getMessage());
			$this->setErrorMessage ($e->getMessage());
		}
		return $info;		
	}	
	
	
	public function getUserProfileList ($listUserId, $db=null)
	{
		if ($db == null)
			$db = $this->getUserProfileDatabase();
			
		$list = new ArrayList();	
		try
		{
			$table = UserMgmtDbTableDefs::TABLE_PROFILE;
			$sql = 'SELECT * FROM ' .$table .' WHERE user_id IN (';
			$count = $listUserId->size();
			for ($i = 0; $i < $count; $i++)
			{
				$id = $listUserId->get($i);	
				if ($i > 0)
					$sql .= ",";
				$sql .= $id;
			}
			$sql .= ")";
			
			//Log::debug ('SQL:' .$sql);
			$result = $db->query($sql);		
			$rowset = $result->fetchAll();	
			foreach ($rowset as $row) 
			{ 
				$info = new UserProfileInfo();
				$this->populateUserProfileInfo ($row, $info);
				$list->add ($info);
			}					
		}
		catch (Exception $e)
		{
			Log::error ('getUserLoginInfo error:' .$e->getMessage());
			$this->setErrorMessage ($e->getMessage());
		}
		return $list;				
	}
	
	//return ArrayList contains UserProfileInfo
	public function getUserProfileInfoByEmail ($email, $db = null)
	{
		//Log::debug ('UserMgmtDbAccessor::getUserProfileInfoByEmail');
		if ($db == null)
			$db = $this->getUserProfileDatabase();
			
		$list = new ArrayList();	
		try
		{
			$table = UserMgmtDbTableDefs::TABLE_PROFILE;
			$sql = 'SELECT * FROM ' .$table .' WHERE primary_email=\''.$email .'\'';
			$result = $db->query($sql);		
			$rowset = $result->fetchAll();	
			foreach ($rowset as $row) 
			{ 
				$info = new UserProfileInfo();
				$this->populateUserProfileInfo ($row, $info);
				$list->add ($info);
			}					
		}
		catch (Exception $e)
		{
			Log::error ('getUserLoginInfo error:' .$e->getMessage());
			$this->setErrorMessage ($e->getMessage());
		}
		return $list;		
	}	
	
	public function saveUserProfileInfo ($info, $db = null)
	{
		if ($db == null)
			$db = $this->getUserProfileDatabase();
			
		try
		{
			$table = UserMgmtDbTableDefs::TABLE_PROFILE;
			$params = $this->binUserProfileInfoParams ($info);
			$db->insert($table, $params);
			$last_insert_id = $db->lastInsertId();	
			$info->setId ($last_insert_id);
		}
		catch (Exception $e)
		{
			Log::error ('saveUserProfileInfo error:' .$e->getMessage());
			$this->setErrorMessage ('Save user profile info error:' .$e->getMessage());
		}
		
		return $info;
	}
	
	public function updateUserProfileInfo ($info, $db=null)
	{
		//Log::debug ('UserMgmtDbAccessor::updateUserProfileInfo:' .$info->getId());
		if ($info == null || $info->getId() == null)
		{
			Log::error ('Cannot update user profile: No profile Id defined');
			$this->setErrorMessage ('Cannot update user profile: No profile Id defined');
			return $info;
		}
		if ($db == null)
			$db = $this->getUserProfileDatabase();
					
		try 
		{  	
			$params = $this->binUserProfileInfoParams ($info);
			$table = UserMgmtDbTableDefs::TABLE_PROFILE;
			$where = $db->quoteInto('id = ?', $info->getId());
			$rows_affected = $db->update($table, $params, $where);
		} 
		catch (Exception $e) 
		{    		
			Log::error ('update profile info error:' .$e->getMessage());		 
			$this->setErrorMessage ('Update profile info erro:' .$e->getMessage());
		}	
		
		return $info;				
	}		
	
	public function getAccountImage ($userId, $db = null)
	{
		if ($db == null)
			$db = $this->getUserProfileDatabase();
		$image = null;			
		try 
		{  	
			$table = UserMgmtDbTableDefs::TABLE_PROFILE;
			$sql = "SELECT image_file FROM " .$table ." WHERE user_id=" .$userId;
			$result = $db->query($sql);		
			$rowset = $result->fetchAll();	
			foreach ($rowset as $row) 
			{
				$image = $row['image_file'];
			}
		} 
		catch (Exception $e) 
		{    		
			Log::error ('getAccountImage error:' .$e->getMessage());		 
			$this->setErrorMessage ('getAccountImage erro:' .$e->getMessage());
		}	
		
		return $image;				
	}
	
	public function saveAccountImage ($userId, $image, $db=null)
	{
		if ($db == null)
			$db = $this->getUserProfileDatabase();
		$bOK = false;		
		try 
		{  	
			$params = array(UserMgmtDbTableDefs::IMAGE_FILE => $image);
			$table = UserMgmtDbTableDefs::TABLE_PROFILE;
			$where = $db->quoteInto('user_id = ?', $userId);
			$rows_affected = $db->update($table, $params, $where);
			$bOK = true;
		} 
		catch (Exception $e) 
		{    		
			Log::error ('saveAccountImage info error:' .$e->getMessage());		 
			$this->setErrorMessage ('saveAccountImage erro:' .$e->getMessage());
		}	
		
		return $bOK;						
	}
	
	////// for open ID //////////////////////////////
	public function linkUserIdWithOpenId ($userId, $openId)
	{
		if ($userId == null || $openId == null)
		{
			Log::debug ('userId or openId = null');
			return false;
		}
		
		$bOK = false;
		$db = $this->getLoginDatabase();
		try
		{
			$user_id = $this->getUserIdByOpenId ($openId);
			if ($user_id != null)
			{
				if ($userId == $user_id)
					$bOK = true;
				else
				{
					$this->setErrorMessage ('The openID has been linked with other user');
					$bOK = false;
				}		
			}	
			else
			{
				//save
				$table = UserMgmtDbTableDefs::TABLE_OPENID;
				$params = array(UserMgmtDbTableDefs::OPENID_URL =>$openId, 
								UserMgmtDbTableDefs::USER_ID =>$userId);
				$db->insert($table, $params);
				$bOK = true;
			}
		}
		catch(Exception $e)
		{
			
		}
		
		return $bOK;	
	}
	
	
	public function getUserIdbyOpenId ($openId, $db=null)
	{
		if ($db == null)
			$db = $this->getLoginDatabase();
		$userId = null;
		try
		{
			$table = UserMgmtDbTableDefs::TABLE_OPENID;
			$sql = "SELECT user_id FROM " .$table ." WHERE openid_url=" .$openId;
			$result = $db->query($sql);		
			$rowset = $result->fetchAll();	
			foreach ($rowset as $row) 
			{
				$userId = $row['user_id'];
			}
			
		}
		catch (Exception $e)
		{
			
		}
		
		return $userId;
	}
	
	////////////////////////////////////////////////////
	//private
	
	private function getLoginDatabase ()
	{
		return $this->getDatabase (UserMgmtDbAccessor::DB_USER_LOGIN);
	}
	
	private function getUserProfileDatabase ()
	{
		return $this->getDatabase (UserMgmtDbAccessor::DB_USER_PROFILE);
	}
	
	private function populateUserLoginInfo ($row, $info)
	{
		$info->setId ($row[UserMgmtDbTableDefs::USER_ID]);
		$info->setUserName ($row[UserMgmtDbTableDefs::USER_NAME]);
		$info->setAccountType ($row[UserMgmtDbTableDefs::ACCOUNT_TYPE]);
		$info->setPassword ($row[UserMgmtDbTableDefs::PASSWORD]);
		$info->setQuestion ($row[UserMgmtDbTableDefs::QUESTION]);
		$info->setAnswer ($row[UserMgmtDbTableDefs::ANSWER]);
		$info->setCreateTime ($row[UserMgmtDbTableDefs::CREATED_TIME]);
		$info->setLastVisitTime ($row[UserMgmtDbTableDefs::LAST_VISIT_TIME]);
		$info->setLastLogoutTime ($row[UserMgmtDbTableDefs::LAST_LOGOUT_TIME]);
		$info->setStatus ($row[UserMgmtDbTableDefs::STATUS_CODE]);
	}
	
	private function populateUserProfileInfo ($row, $info)
	{
		$info->setId ($row[UserMgmtDbTableDefs::ID]);
		$info->setUserId ($row[UserMgmtDbTableDefs::USER_ID]);
		$info->setFirstName ($row[UserMgmtDbTableDefs::FIRST_NAME]);
		$info->setLastName ($row[UserMgmtDbTableDefs::LAST_NAME]);
		$info->setEmail ($row[UserMgmtDbTableDefs::PRIMARY_EMAIL]);
		$info->setLanguage ($row[UserMgmtDbTableDefs::LANGUAGE]);
		$info->setCountry ($row[UserMgmtDbTableDefs::COUNTRY]);
		$info->setState ($row[UserMgmtDbTableDefs::STATE]);
		$info->setCity ($row[UserMgmtDbTableDefs::CITY]);
		$info->setTimezone ($row[UserMgmtDbTableDefs::TIME_ZONE]);
		$info->setImage ($row[UserMgmtDbTableDefs::IMAGE_FILE]);
		$info->setUpdateTime ($row[UserMgmtDbTableDefs::UPDATE_TIME]);
		$info->setStatus ($row[UserMgmtDbTableDefs::STATUS_CODE]);
	}	
	
	private function binUserLoginInfoParams ($info)
	{
		$row = array (    
			UserMgmtDbTableDefs::USER_NAME => $info->getUserName(),
			UserMgmtDbTableDefs::PASSWORD => $info->getPassword(),			
			UserMgmtDbTableDefs::CREATED_TIME => $info->getCreateTime(),
			UserMgmtDbTableDefs::LAST_VISIT_TIME => $info->getLastVisitTime(),
			UserMgmtDbTableDefs::QUESTION => $info->getQuestion(),
			UserMgmtDbTableDefs::ANSWER => $info->getAnswer(),
			UserMgmtDbTableDefs::ACCOUNT_TYPE => $info->getAccountType(),
		);
		return $row;
	}

	private function binUserProfileInfoParams ($info)
	{
		$row = array (    
			UserMgmtDbTableDefs::USER_ID => $info->getUserId(),
			UserMgmtDbTableDefs::FIRST_NAME => $info->getFirstName(),
			UserMgmtDbTableDefs::LAST_NAME => $info->getLastName(),
			UserMgmtDbTableDefs::LANGUAGE => $info->getLanguage (),
			UserMgmtDbTableDefs::COUNTRY => $info->getCountry (),
			UserMgmtDbTableDefs::STATE => $info->getState (),
			UserMgmtDbTableDefs::CITY => $info->getCity (),
			UserMgmtDbTableDefs::TIME_ZONE => $info->getTimezone(),
			UserMgmtDbTableDefs::IMAGE_FILE => $info->getImage(),
			UserMgmtDbTableDefs::PRIMARY_EMAIL => $info->getEmail(),
			UserMgmtDbTableDefs::UPDATE_TIME => $info->getUpdateTime(),
		);
				
		return $row;		
	}	
	
	//old
	
	private function populateUserInfo ($row, $userInfo)
	{
		$userInfo->setId ($row[UserMgmtDbTableDefs::USER_ID]);
		$userInfo->setUserName ($row[UserMgmtDbTableDefs::USER_NAME]);
		$userInfo->setPassword ($row[UserMgmtDbTableDefs::PASSWORD]);
		$userInfo->setFirstName ($row[UserMgmtDbTableDefs::FIRST_NAME]);
		$userInfo->setMiddleName ($row[UserMgmtDbTableDefs::MIDDLE_NAME]);
		$userInfo->setLastName ($row[UserMgmtDbTableDefs::LAST_NAME]);
		$userInfo->setEmail ($row[UserMgmtDbTableDefs::PRIMARY_EMAIL]);
		$userInfo->setLocale ($row[UserMgmtDbTableDefs::LOCALE]);
		$userInfo->setTimezone ($row[UserMgmtDbTableDefs::TIME_ZONE]);
		$userInfo->setCreateDate ($row[UserMgmtDbTableDefs::CREATED_DATE]);
		$userInfo->setLastVisitTime ($row[UserMgmtDbTableDefs::LAST_VISIT_TIME]);
	}	
	
	private function binUserInfoParams ($userInfo)
	{
		$row = array (    
			UserMgmtDbTableDefs::USER_NAME => $userInfo->getUserName(),
			UserMgmtDbTableDefs::PASSWORD => $userInfo->getPassword(),			
			UserMgmtDbTableDefs::FIRST_NAME => $userInfo->getFirstName(),
			UserMgmtDbTableDefs::MIDDLE_NAME => $userInfo->getMiddleName(),
			UserMgmtDbTableDefs::LAST_NAME => $userInfo->getLastName(),
			UserMgmtDbTableDefs::LOCALE => $userInfo->getLocale (),
			UserMgmtDbTableDefs::TIME_ZONE => $userInfo->getTimezone(),
			UserMgmtDbTableDefs::PRIMARY_EMAIL => $userInfo->getEmail(),
			UserMgmtDbTableDefs::CREATED_DATE => $userInfo->getCreateDate(),
			UserMgmtDbTableDefs::LAST_VISIT_TIME => $userInfo->getLastVisit(),
		);
				
		return $row;		
	}
} 
 
?>