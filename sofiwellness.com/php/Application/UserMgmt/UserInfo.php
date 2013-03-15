<?php
/*
 $id:$
 * FILE:UserInfo.php
 * CREATE:Jun 4, 2008
 * BY:ghuang
 * 
 * NOTE:
 */
 
 
require_once 'LoginInfo.php';
require_once 'UserProfileInfo.php';
require_once 'AccountTypes.php';

class UserInfo 
{	
	private $_id = 0;
	private $_accountType = AccountTypes::CUSTOMER;
	private $_roleName = null;
	private $_userName;
	private $_password;	
	private $_lastVisit = null;
	private $_lastLogout;
	
	private $_displayName = null;
	private $_firstName;
	private $_lastName;
	private $_title;
	private $_email;
	private $_phone;
	private $_cell;
	private $_language;
	private $_address;
	private $_description = null;
	private $_status=0;
	private $_createTime = null;
	
	private $_imageInfo = null; //for image of user
	
	public function setId ($id)
	{
		$this->_id = $id;	
	}
	
	public function getId ()
	{
		return $this->_id;
	}
	
	public function setAccountType ($type)
	{
		$this->_accountType = $type;	
	}
	
	public function getAccountType ()
	{
		return $this->_accountType;	
	}
	
	
	public function setRoleName ($name)
	{
		$this->_roleName = $name;
	}
	
	public function getRoleName ()
	{
		return $this->_roleName;
	}
	
	public function setUserName ($name)
	{
		$this->_userName = $name;
	}
	
	public function getUserName ()
	{
		return $this->_userName;
	}
	
	public function setPassword ($password)
	{
		$this->_password = $password;
	}
	
	public function getPassword ()
	{
		return $this->_password;
	}
	
	public function setDisplayName ($val)
	{
		$this->_displayName = $val;
	}
	
	public function getDisplayName ()
	{
		if ($this->_displayName != null)
			return $this->_displayName;	
		if ($this->_firstName != null || $this->_lastName != null)
			$name = $this->_firstName .' ' .$this->_lastName;
		else
			$name = $this->_userName;
		return $name;
	}
	
	public function setFirstName ($name)
	{
		$this->_firstName = $name;
	}
	
	public function getFirstName ()
	{
		return $this->_firstName;
	}
	
	public function setLastName ($name)
	{
		$this->_lastName = $name;
	}
	
	public function getlastName ()
	{
		return $this->_lastName;
	}
	
	public function setTitle ($val)
	{
		$this->_title = $val;
	}
	
	public function getTitle ()
	{
		return $this->_title;
	}
	
	public function setEmail ($email)
	{
		$this->_email = $email;
	}
	
	public function getEmail ()
	{
		return $this->_email;
	}
	
	public function setPhone ($val)
	{
		$this->_phone = $val;
	}
	
	public function getPhone ()
	{
		return $this->_phone;
	}

	public function setCell ($val)
	{
		$this->_cell = $val;
	}
	
	public function getCell ()
	{
		return $this->_cell;
	}
	
	public function setLanguage ($language)
	{
		$this->_language = $language;
	}
	
	public function getLanguage ()
	{
		return $this->_language;
	}
	
	public function setAddress ($val)
	{
		$this->_address = $val;
	}
	
	public function getAddress ()
	{
		return $this->_address;
	}
	
	public function setDescription ($val)
	{
		$this->_description = $val;
	}
	
	public function getDescription ()
	{
		return $this->_description;
	}
	

	
	public function setCreateTime ($date)
	{
		$this->_createTime = $date;
	}

	public function getCreateTime ()
	{
		if ($this->_createTime == null)
			$this->_createTime = DatetimeUtil::getCurrentTimeStr();
			
		return $this->_createTime;
	}

	public function setLastVisitTime ($val)
	{
		$this->_lastVisit = $val;
	}

	public function getLastVisitTime ()
	{
		return $this->_lastVisit;
	}
	
	public function setLastLogoutTime ($val)
	{
		$this->_lastLogout = $val;
	}

	public function getLastLogoutTime ()
	{
		return $this->_lastLogout;
	}
	
	public function setStatus ($status)
	{
		$this->_status = $status;
	}
	
	public function getStatus ()
	{
		return $this->_status;
	}
	
	public function setImageInfo ($imageInfo)
	{
		$this->_imageInfo = $imageInfo;
	}
	
	public function getImageInfo ()
	{
		return $this->_imageInfo;
	}
}
?>
