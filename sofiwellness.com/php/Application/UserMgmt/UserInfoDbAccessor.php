<?php
/*
 * $Id:$
 * FILE:UserInfoDbAccessor.php
 * CREATE: May 16, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class UserInfoDbAccessor extends BaseDbAccessor
{
	const TABLE_USERINFO = "user_info";
	
	
	public function getUserInfoBy ($userName, $password)	
	{
		$table = UserInfoDbAccessor::TABLE_USERINFO;
		$sql = "SELECT * FROM " .$table;
		$sql .= " WHERE user_name='".$userName ."'";
		if ($password != null)
			$sql .= " AND password='". $password ."'";
		Log::debug ('SQL:' .$sql);
		$info = parent::get($sql, array($this, 'retrieveUserInfoCB'));	
		if ($info == null)
		Log::debug ('info = null!');
		return $info;
	}
	
	public function getUserId ($userName)
	{
		$user_id = 0;
		$table = UserInfoDbAccessor::TABLE_USERINFO;
		$sql = "SELECT * FROM " .$table;
		$sql .= " WHERE user_name='".$userName ."'";
		Log::debug ('SQL:' .$sql);
		$info = parent::get($sql, array($this, 'retrieveUserInfoCB'));	
		if ($info != null)
			$user_id = $info->getId ();
		
		return $user_id;
	}
	
	
	public function getUserInfoById ($userId)
	{
		$table = UserInfoDbAccessor::TABLE_USERINFO;
		$sql = "SELECT * FROM " .$table;
		$sql .= " WHERE id=".$userId;
		Log::debug ('SQL:' .$sql);
		$info = parent::get($sql, array($this, 'retrieveUserInfoCB'));
		return $info;				
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
	public function getUserInfoByNames ($firstName, $lastName, $phone)
	{
		$table = UserInfoDbAccessor::TABLE_USERINFO;
		$sql = "SELECT * FROM " .$table;
		$sql .= " WHERE first_name='" .$firstName ."'";
		if ($lastName != null)
			$sql .=" AND last_name='".$lastName ."'";
		if ($phone != null)
			$sql .= " AND (phone='" .$phone ."' OR cell='".$phone ."')";
		
		$info = parent::get($sql, array($this, 'retrieveUserInfoCB'));
		return $info;				
	}
	
	public function getUserInfoByParams ($info)
	{
		$table = UserInfoDbAccessor::TABLE_USERINFO;
		$sql = "SELECT * FROM " .$table;
		$sql .= " WHERE ";
		$or = '';
		if ($info->getFirstName () != null)
		{
			$sql.=" first_name='" .$info->getFirstName() ."'";
			$or = ' OR ';
		}
		if ($info->getLastName() != null)
		{
			$sql .= $or;
			$sql .=" last_name='".$info->getLastName() ."'";
			$or = ' OR ';			
		}
		if ($info->getPhone() != null)
		{
			$phone = $info->getPhone();
			$sql .= $or;
			$sql .= " (phone='" .$phone ."' OR cell='".$phone ."')";
			$or = ' OR ';
		}
		if ($info->getEmail() != null)
		{
			$sql .= $or;
			$sql .=" email='".$info->getEmail() ."'";			
		}
		
		
		$list = parent::get($sql, array($this, 'retrieveUserInfoListCB'));
		return $list;				
		
	}
	
	public function getUserInfoList ($accountType)
	{
		$table = UserInfoDbAccessor::TABLE_USERINFO;
		$sql = "SELECT * FROM " .$table;
		$sql .= " WHERE account_type='".$accountType ."'";
		Log::debug ('SQL:' .$sql);
		$list = parent::get ($sql, array($this, 'retrieveUserInfoListCB'));
		return $list;
	}
	
	public function saveUserInfo ($info)
	{
		Log::debug ('saveUserInfo');
		$table = UserInfoDbAccessor::TABLE_USERINFO;
		$params = $this->binUserInfoParams ($info);
		Log::debug ('## call save');
		$id = parent::save ($table, $params);
		Log::debug ('after sve');
		$info->setId ($id);		
		return $info;
	}
	
	public function updateUserInfo ($info)
	{
		$table = UserInfoDbAccessor::TABLE_USERINFO;
		$params = $this->binUserInfoParams ($info);
		parent::update ($table, $info->getId(), $params);
		
		return $info;
	}	
	
	public function updateLastVisitTime($userId, $lastVisitTime)
	{
		$table = UserInfoDbAccessor::TABLE_USERINFO;
		$params = array (
			'last_visit_time' => $lastVisitTime
		);
		return parent::update ($table, $userId, $params);
	}
	
	public function deleteUser ($id)
	{
		$table = UserInfoDbAccessor::TABLE_USERINFO;
		Log::debug ('delete from:' .$table);
		return parent::delete ($table, $id);	
	}
	
	public function updatePassword ($id, $password)
	{
		$table = UserInfoDbAccessor::TABLE_USERINFO;
		$params = array (
			'password' => $password
		);
		return parent::update ($table, $id, $params);
	}
	
	
	///// callbacks
	public function retrieveUserInfoCB ($rows)
	{
		$ret = null;
		foreach ($rows as $row)
		{
			$ret = new UserInfo();
			$this->populateUserInfo ($row, $ret);
		}
		return $ret;
	}
	
	public function retrieveUserInfoListCB ($rows)
	{
		$ret = new ArrayList ();
		foreach ($rows as $row)
		{
			$info = new UserInfo();
			$this->populateUserInfo ($row, $info);
			$ret->add ($info);
		}
		return $ret;
	}

	////////////////////////////////////////////////////////////////////
	//
	protected function getDb ()
	{
		return parent::getDatabase ();	
	}
	
	private function binUserInfoParams ($userInfo)
	{
		$row = array (    
			'user_name' => $userInfo->getUserName(),
			'password' => $userInfo->getPassword(),	
			'account_type' =>$userInfo->getAccountType(),
			'role_name' => $userInfo->getRoleName(),	
			'display_name' => $userInfo->getDisplayName(),	
			'first_name' => $userInfo->getFirstName(),
			'last_name' => $userInfo->getLastName(),
			'title' => $userInfo->getTitle(),
			'phone' => $userInfo->getPhone (),
			'cell' => $userInfo->getCell(),
			'email' => $userInfo->getEmail(),
			'address' => $userInfo->getAddress(),
			'language' => $userInfo->getLanguage(),
			'description' => $userInfo->getDescription(),
			'create_time' => $userInfo->getCreateTime(),
			'status_code' => $userInfo->getStatus(),
			'last_visit_time' => $userInfo->getLastVisitTime(),
		);
				
		return $row;		
	}
	
	private function populateUserInfo ($row, $userInfo)
	{
		$userInfo->setId ($row["id"]);
		$userInfo->setAccountType ($row['account_type']);
		$userInfo->setRoleName ($row['role_name']);
		$userInfo->setUserName ($row['user_name']);
		$userInfo->setPassword ($row['password']);
		$userInfo->setDisplayName ($row['display_name']);
		$userInfo->setFirstName ($row['first_name']);
		Log::debug('displayName:' .$row['display_name']);
		$userInfo->setLastName ($row['last_name']);
		$userInfo->setTitle ($row['title']);
		$userInfo->setEmail ($row['email']);
		$userInfo->setPhone ($row['phone']);
		$userInfo->setCell ($row['cell']);
		$userInfo->setAddress ($row['address']);
		$userInfo->setLanguage ($row['language']);
		$userInfo->setDescription ($row['description']);
		$userInfo->setStatus ($row['status_code']);
		
		$userInfo->setCreateTime ($row['create_time']);
		$userInfo->setLastVisitTime ($row['last_visit_time']);
	}	
}
?>