<?php
/*
 * $Id:$
 * FILE:ServiceTimeForm.php
 * CREATE: May 30, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once APP_COMMON_DIR .'/Models/ArrayList.php'; 

class ServiceTimeForm 
{
	const ON = true;
	const OFF = false;
	
	private $_userId;
	private $_list;
	
	public function __construct ()
	{
		$this->_list = new ArrayList();
	}
		
	public function setUserId ($val)
	{
		$this->_userId = $val;
	}
	
	public function getUserId ()
	{
		return $this->_userId;
	}
	public function addItem ($item)
	{
		$this->_list->add($item);
	}
	
	public function addItemBy ($date, $on, $startTime, $endTime, $note=null)
	{
		$item = new ServiceTimeFormItem();
		$item->setDate ($date);
		$item->setOn ($on);
		$item->setStartTime ($startTime);
		$item->setEndTime ($endTime);
		$item->setNote ($note);
	}
	
	public function getItems ()
	{
		return $this->_list;
	}
		
	public function isAvailable ($date)
	{
		$item = $this->findItem ($date);
		if ($item != null)
			return $item->getOn();
		else
			return false;
	}
	
	public function getStartTime ($date)
	{
		$item = $this->findItem ($date);
		if ($item != null)
			return $item->getStartTime();
		else
			return null;
	}

	public function getEndTime ($date)
	{
		$item = $this->findItem ($date);
		if ($item != null)
			return $item->getEndTime();
		else
			return null;
	}
	
	public function getNote ($date)
	{
		$item = $this->findItem ($date);
		if ($item != null)
			return $item->getNote();
		else
			return null;
	}
	
	//for form checkbox
	public function getChecked ($date)
	{
		$bOn = $this->isAvailable ($date);
		if ($bOn)
			return 'checked';
		else
			return '';
	}
	
	public function findItem ($date)
	{
		if ($this->_list->size () == 0)
			return null;
			
		$count = $this->_list->size();
		for ($i = 0; $i < $count; $i++)
		{	
			$item = $this->_list->get($i);	
			$d = $item->getDate();
			if ($d == $date)
				return $item;
		}
		
		return null;
		
	}
}
?>