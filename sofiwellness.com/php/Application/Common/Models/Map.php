<?php
/*
 * Created on Feb 14, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class Map
{
	protected $_list = array();
	
	public function __construct ()
	{
		
	}
	
	public function set($key, $val)
	{
		$this->put ($key, $val);
	}
	
	public function put ($key, $val)
	{
		$this->_list[$key] = $val;
	}
	
	public function get ($key)
	{
		if ($key == null || 0 == count($this->_list))
			return null;
			
		foreach ($this->_list as $ikey => $value)
		{
  			if ($key == $ikey)
  			{
    			return $value;
  			}
  		}
		return null;
		
	}
	
	public function getAll ()
	{
		return $this->_list;
	}
	
	public function size ()
	{
		return count ($this->_list);
	}
	
	public function hasKey ($key)
	{
		if (array_key_exists($key, $this->_list))
			return true;
		else
			return false;
	}
	
	//return array contains keys
	public function getKeys ()
	{
		return array_keys ($this->_list);
	}
	
	public function getValues ()
	{
		return array_values ($this->_list);
	}
	
	public function remove ($key)
	{
		unsset($this->_list[$key]);
	}
	
	public function clear ()
	{
		unset ($this->_list);
	}
	
	public function toString ()
	{
		 if ($this->size() == 0)
		 	return '';
		 
		 $str = '';
		 foreach ($this->_list as $key => $val)
		 {
		 	if (strlen ($str) > 0)
		 		$str .= ',';
		 	$str .= $key .'=' .$val;
		 }
	}
} 
 
?>
