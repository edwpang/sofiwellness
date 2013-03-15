<?php
/*
 * Created on Sep 14, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 
 
require_once 'UserMgmtInclude.php'; 


class UserManager 
{
	public function _construct ()
	{
	}
		
	public static function getUserInfo ($userName, $password)
	{
		$db = new UserInfoDbAccessor();
		return $db->getUserInfoBy ($userName, $password);
	}
	
	public static function getUserInfoById ($userId)
	{
		//Log::debug ('UserManager::getUserInfoById:'.$userId);
		$db = new UserInfoDbAccessor ();
		$info = $db->getUserInfoById($userId);
		
		return $info;		
	}
	
	public static function getUserInfoByUserName ($userName)
	{
		//Log::debug ('UserManager::getUserInfoByUserName:'.$userName);
		$db = new UserInfoDbAccessor ();
		$info = $db->getUserInfo($userName);
		return $info;		
	}
	
	public static function getUserInfoListByType ($accountType)
	{
		$db = new UserInfoDbAccessor ();
		return $db->getUserInfoList ($accountType);		
	}
	
	
	public static function getUserInfoByNames ($firstName, $lastName, $phone=null)
	{
		$db = new UserInfoDbAccessor ();
		return $db->getUserInfoByNames ($firstName, $lastName, $phone);		
	}
	
	//return ArrayList contains UserInfo
	public static function getUserInfoByParams ($info)
	{
		$db = new UserInfoDbAccessor ();
		$infoList = $db->getUserInfoByParams($info);
		return $infoList;		
	}

	public static function getLoginInfoById ($userId)
	{
		//Log::debug ('UserManager::getLoginInfoById:'.$userId);
		$db = new UserInfoDbAccessor ();
		$info = $db->getUserLoginInfo($userId);
		return $info;		
	}	
	

	//get ArrayList contains UsereInfo by UserIdList (ArrayList)
	public static function getUserInfoList ($accountType)
	{
		$db = new UserInfoDbAccessor ();
		$list = $db->getUserInfoList ($accountType);
		return 	$list;
	}
	
	public static function hasUserName ($name)
	{
		$db = new UserInfoDbAccessor ();
		return $db->userNameExist($name);
	}
		
	public static function saveUserInfo ($userInfo)
	{
		$db = new UserInfoDbAccessor ();
		$info = $db->saveUserInfo($userInfo);	
		
		return $info;
	}
	
	public static function deleteUser ($id)
	{
		$db = new UserInfoDbAccessor();
		return $db->deleteUser ($id);
	}
	
	public static function updateUserInfo ($info)
	{
		$db = new UserInfoDbAccessor ();
		return $db->updateUserInfo($info);		
	}
	
	public static function updatePassword ($userId, $password)
	{
		$db = new UserInfoDbAccessor ();
		return $db->updatePassword($userId, $password);				
	}
	
	
	public static function updateLastVisitTime ($userId, $lastVisitTime=null)
	{
		if ($lastVisitTime == null)
			$lastVisitTime = DatetimeUtil::getCurrentTimeStr();
		$db = new UserInfoDbAccessor ();
		return $db->updateLastVisitTime($userId, $lastVisitTime);
	}
	
	public static function isAdmin ()
	{
		$userInfo = Utils::getUserInfo();
		if ($userInfo == null)
			return false;
			
		$roleName = $userInfo->getRoleName();
		//Log::debug ('isAdmin:roleNmae:'.$roleName);
		if ($roleName != null)
		{
			$roles = explode (",", $roleName);
			foreach ($roles as $role)
			{
				$name = trim ($role);
				if (RoleNames::isAdmin($name))
					return true;
			}
		}
		return false;
	}

	public static function isAdminOrKeeper ()
	{
		$userInfo = Utils::getUserInfo();
		if ($userInfo == null)
			return false;
		$roleName = $userInfo->getRoleName();
		if ($roleName != null)
		{
			$roles = explode (",", $roleName);
			foreach ($roles as $role)
			{
				$name = trim ($role);
				if (RoleNames::isAdminOrBookKeeper($name))
					return true;
			}
		}
		return false;
	}
	
	public static function isResource ()
	{
		$userInfo = Utils::getUserInfo();
		if ($userInfo == null)
			return false;
		$type = $userInfo->getAccountType();
		Log::debug ('isResource: type=' .$type);
		if (AccountTypes::isProfessional($type))
			return true;
		else
			return false;				
	}
	
	public static function getStatusArray ()
	{
		$ar = array (
		 0 => 'new',
		 1 => 'active',
		 2 => 'disabled'
		);
		
		return $ar;
	}
} 
 
?>
