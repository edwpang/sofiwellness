<?php
/*
 * $Id: ReminderInfo.php,v 1.2 2009/04/07 15:04:23 gorsen Exp $
 * FILE:ReminderInfo.php
 * CREATE: Feb 17, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class AppointmentInfo
{
	const STATUS_NEW = 0;
	const STATUS_CONFIRM = 1;
	const STATUS_DONE = 2;
	const STATUS_CANCEL = 3;
	
	private $_id = null;
	private $_userId;
	private $_typeName;
	private $_resourceId;
	private $_firstName;
	private $_lastName;
	private $_subject;
	private $_detail;
	private $_theDate; //DATE   yyyy-mm-dd
	private $_startTime; //TIME h:i
	private $_endTime; //TIME
	private $_timeLength = 0;
	private $_status = AppointmentInfo::STATUS_NEW;
	private $_createTime = null;
	
	//for form 
	private $_resName; //name of resource_id
	
	
	public function _construct ()
	{
		$this->_createTime = DatetimeUtil::getCurrentTimeStr();
	}
	
	
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
	
	public function getUserId()
	{
		return $this->_userId;	
	}
	
	public function setFirstName ($name)
	{
		$this->_firstName = $name;	
	}
	
	public function getFirstName()
	{
		return $this->_firstName;	
	}
	
	public function setLastName ($name)
	{
		$this->_lastName = $name;	
	}
	
	public function getLastName()
	{
		return $this->_lastName;	
	}
	
	public function setSubject ($subject)
	{
		$this->_subject = $subject;	
	}
	
	public function getSubject ()
	{
		return $this->_subject;	
	}
	
	public function setDetail ($detail)
	{
		$this->_detail = $detail;	
	}
	
	public function getDetail ()
	{
		return $this->_detail;	
	}
	
	
	public function setTheDate ($val)
	{
		$this->_theDate = $val;
	}
	
	public function getTheDate ()
	{
		return $this->_theDate;
	}
	
	public function setStartTime ($date)
	{
		$this->_startTime = $date;	
	}

	public function getStartTime ()
	{
		return $this->_startTime;	
	}
	
	public function setEndTime ($date)
	{
		$this->_endTime = $date;	
	}
	
	public function getEndTime ()
	{
		return $this->_endTime;	
	}

	//in minutes
	public function setTimeLength($length)
	{
		$this->_timeLength = $length;
	}
	
	public function getTimeLength()
	{
		return $this->_timeLength;
	}
	
	public function setResourceId ($val)
	{
		$this->_resourceId = $val;	
	}
	
	public function getResourceId ()
	{
		return $this->_resourceId;	
	}
	
	public function setTypeName($type)
	{
		$this->_typeName = $type;	
	}
	
	public function getTypeName ()
	{
		return $this->_typeName;
	}
	
	public function setStatus ($status)
	{
		$this->_status = $status;	
	}
	
	public function getStatus ()
	{
		return $this->_status;
	}
	
	public function setCreateTime ($time)
	{
		$this->_createTime = $time;	
	}
	
	public function getCreateTime ()
	{
		if ($this->_createTime == null)
			$this->_createTime = DatetimeUtil::getCurrentTimeStr();
			
		return $this->_createTime;	
	}
	
	
	public function setResourceName ($name)
	{
		$this->_resName = $name;
	}
	
	public function getResourceName ()
	{
		return $this->_resName;
	}
	
	public function makeCopy ($info)
	{
		$this->_id = $info->getId();
		$this->_userId = $info->getUserId();
		$this->_typeName = $info->getTypeName();
		$this->_resourceId = $info->getResourceId();
		$this->_firstName = $info->getFirstName();
		$this->_lastName = $info->getLastName();
		$this->_subject = $info->getSubject();
		$this->_detail = $info->getDetail();
		$this->_theDate = $info->getTheDate();
		$this->_startTime = $info->getStartTime();
		$this->_endTime = $info->getEndTime();
		$this->_timeLength = $info->getTimeLength();
		$this->_status = $info->getStatus();
		$this->_createTime = $info->getCreateTime();
	}

}
?>