<?php
/*
 * $Id:$
 * FILE:AppointmentForm.php
 * CREATE: May 26, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AppointmentInfo.php';

class AppointmentForm extends AppointmentInfo
{
	private $_day;
	private $_timeFrom;
	private $_timeTo;
	private $_userInfo = null;
	
	public function setDay ($day)
	{
		$this->_day = $day;
	}
	
	public function getDay ()
	{
		return $this->_day;
	}
	
	public function setTimeTo ($val)
	{
		$this->_timeTo = $val;
	}
	public function getTimeTo ()
	{
		return $this->_timeTo;
	}
	
	public function setTimeFrom ($val)
	{
		$this->_timeFrom = $val;
	}
	
	public function getTimeFrom ()
	{
		return $this->_timeFrom;
	}
	

	public function setUserInfo ($info)
	{
		$this->_userInfo = $info;
		if ($this->_userInfo != null && $this->_userInfo->getId() != 0)
		{
			parent::setFirstName ($info->getFirstName());
			parent::setLastName ($info->getLastName());	
			parent::setUserId ($info->getId());
		}
	}
	
	public function getUserInfo()
	{
		if ($this->_userInfo == null)
			$this->_userInfo = new UserInfo();
		return $this->_userInfo;
	}
	
}
?>