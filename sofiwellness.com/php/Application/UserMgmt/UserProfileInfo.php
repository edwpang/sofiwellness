<?php
/*
 * $Id: UserProfileInfo.php,v 1.2 2008/11/18 20:07:34 gorsen Exp $
 * FILE:UserProfileInfo.php
 * CREATE: Oct 2, 2008
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class UserProfileInfo
{
	private $_id;
	private $_userId;
	private $_firstName;
	private $_lastName;
	private $_email;
	private $_language;
	private $_country;
	private $_state;
	private $_city;
	private $_timezone;
	private $_image; //image file
	private $_updateTime = null;
	private $_status = 0;
	
	
	public function setId ($id)
	{
		$this->_id = $id;	
	}
	
	public function getId ()
	{
		return $this->_id;
	}
	
	public function setUserId ($userId)
	{
		$this->_userId = $userId;
	}
	
	public function getUserId ()
	{
		return $this->_userId;
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
	
	public function setEmail ($email)
	{
		$this->_email = $email;
	}
	
	public function getEmail ()
	{
		return $this->_email;
	}
	
	public function setLocale ($locale)
	{
		$this->_locale = $locale;
	}
	
	public function getLocale ()
	{
		return $this->_locale;
	}
	
	public function setLanguage ($language)
	{
		$this->_language = $language;
	}
	
	public function getLanguage ()
	{
		return $this->_language;
	}
	
	public function setTimezone ($timezone)
	{
		$this->_timezone = $timezone;
	}
	
	public function getTimezone ()
	{
		return $this->_timezone;
	}
	
	public function setCountry ($country)
	{
		$this->_country = $country;
	}
	
	public function getCountry ()
	{
		return $this->_country;
	}
	
	public function setState ($state)
	{
		$this->_state = $state;
	}
	
	public function getState ()
	{
		return $this->_state;
	}
	
	public function setCity ($city)
	{
		$this->_city = $city;
	}
	
	public function getCity ()
	{
		return $this->_city;
	}
	
	public function setUpdateTime ($date)
	{
		$this->_updateTime = $date;
	}

	public function setImage ($image)
	{
		$this->_image = $image;	
	}
	
	public function getImage ()
	{
		return $this->_image;	
	}
	
	public function getUpdateTime ()
	{
		if ($this->_updateTime == null)
			$this->_updateTime = Utils::getCurrentTimeStr();
			
		return $this->_updateTime;
	}
	
	public function setStatus ($status)
	{
		$this->_status = $status;
	}

	public function getStatus ()
	{
		return $this->_status;
	}		
	
	public function copy ($profile)
	{
		$this->_id = $profile->getId();
		$this->_userId = $profile->getUserId();
		$this->_firstName = $profile->getFirstName();
		$this->_lastName = $profile->getLastName();
		$this->_email = $profile->getEmail();
		$this->_language = $profile->getLanguage();
		$this->_country = $profile->getCountry();
		$this->_state = $profile->getState();
		$this->_city = $profile->getCity();
		$this->_timezone = $profile->getTimezone();
		$this->_updateTime = $profile->getUpdateTime();
		$this->_status = $profile->getStatus();	
	}
}
?>