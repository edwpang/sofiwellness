<?php
/*
 * $Id:$
 * FILE:SericeTimeFormItem.php
 * CREATE: May 30, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class ServiceTimeFormItem 
{
	private $_id;
	private $_date;
	private $_on = false;
	private $_startTime;
	private $_endTime;	
	private $_note = null;
	private $_typeName = null;
	
	public function setId ($id)
	{
		$this->_id = $id;
	}
	
	public function getId ()
	{
		return $this->_id;
	}
	
	public function setDate ($date)
	{
		$this->_date = $date;
	}
	
	public function getDate ()
	{
		return $this->_date;
	}
	public function setOn ($val)
	{
		$this->_on = $val;
	}
	
	public function getOn ()
	{
		return $this->_on;
	}
	public function setStartTime ($val)
	{
		$this->_startTime = $val;
	}
	
	public function getStartTime ()
	{
		return $this->_startTime;
	}
	public function setEndTime ($val)
	{
		$this->_endTime = $val;
	}
	
	public function getEndTime ()
	{
		return $this->_endTime;
	}
	public function setNote ($val)
	{
		$this->_note = $val;
	}
	
	public function getNote ()
	{
		return $this->_note;
	}
	
	public function setTypeName ($type)
	{
		$this->_typeName = $type;
	}
	
	public function getTypeName ()
	{
		return $this->_typeName;	
	}
	
	public function toInfo ()
	{
		$info = new ServiceTimeInfo ();
		$info->setTheDate ($this->_date);
		$info->setOnDuty ($this->_on);
		$info->setNote ($this->_note);
		$info->setStartTime($this->_startTime);
		$info->setEndTime ($this->_endTime);
		$info->setTypeName ($this->_typeName);
		return $info;
	}
}
?>