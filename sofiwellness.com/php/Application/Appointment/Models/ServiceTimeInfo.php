<?php
/*
 * $Id:$
 * FILE:ServiceTimeInfo.php
 * CREATE: May 30, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class ServiceTimeInfo
{
	const GENERAL = 'general';  //for resource general setting such available date and time
	const HOLIDAY = 'holiday';  //the_date is the holiday name
	const BUSINESS_HOUR = 'business_hour';  //for start time and end time of week days
	const BUSINESS_HOUR_SAT = 'business_hour_sat';  //for start time and end time of saturday
	const BUSINESS_HOUR_SUN = 'business_hour_sun';  //for start time and end time of sunday
	const BUSINESS_DAY = 'business_day';    //for monday ...
	const DAY_OFF = 'day_off';   //for day off
	
	const ON = 1;
	const OFF = 0;
	
	private $_id;
	private $_userId;
	private $_onDuty = 0;  //true or false - false mean off date
  	private $_theDate; //string
  	private $_startTime;
  	private $_endTime;
  	private $_typeName;  //for GENERAL, HOLIDAY ...
  	private $_note = null;
  	private $_updateTime;
  	
  	public function setId ($val)
  	{
  		$this->_id = $val;
  	}
  	
  	public function getId ()
  	{
  		return $this->_id;
  	}
  	public function setUserId ($val)
  	{
  		$this->_userId = $val;
  	}
  	
  	public function getUserId ()
  	{
  		return $this->_userId;
  	}
  	//true or false
  	public function setOnDuty ($val)
  	{
  		$f = 0;
  		if ($val)
  			$f = 1;
  		$this->_onDuty = $f;
  	}
  	
  	public function getOnDuty ()
  	{
  		return $this->_onDuty;
  	}
  	
  	public function isOnDuty ()
  	{
  		return ($this->_onDuty == 1);
  	}
  	
  	public function setStartTime ($val)
  	{
  		$this->_startTime = $val;
  	}
  	
  	public function getStartTime ()
  	{
  		return $this->_startTime;
  	}
  	
  	public function getStartTimeStr ($format ='g:i a')
  	{
  		if ($this->_startTime != null)
  			return  DatetimeUtil::formatDatetimeStr ($this->_startTime, $format);
  		else
  			return null;
  	}
  	
  	public function setEndTime ($val)
  	{
  		$this->_endTime = $val;
  	}
  	
  	public function getEndTime ()
  	{
  		return $this->_endTime;
  	}
  	
  	public function getEndTimeStr ($format ='g:i a')
  	{
   		if ($this->_endTime != null)
  			return  DatetimeUtil::formatDatetimeStr ($this->_endTime, $format);
  		else
  			return null;
  	}
  	
  	public function setTypeName ($val)
  	{
  		$this->_typeName = $val;
  	}
  	
  	public function getTypeName ()
  	{
  		return $this->_typeName;
  	}
  	
  	public function setTheDate ($val)
  	{
  		$this->_theDate = $val;
  	}
  	
  	public function getTheDate ()
  	{
  		return $this->_theDate;
  	}
  	public function setUpdateTime ($val)
  	{
  		$this->_updateTime = $val;
  	}
  	
  	public function getUpdateTime ()
  	{
  		return $this->_updateTime;
  	}

	public function setNote ($val)
	{
		$this->_note = $val;
	}
	
	public function getNote ()
	{
		return $this->_note;
	}
}
?>