<?php
/*
 * Created on Aug 27, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class ArrayList 
{
	protected $_list = array();
	
	public function __construct ()
	{
		
	}
	
	public function add ($item)
	{
		array_push ($this->_list, $item);
	}
	
	public function insert ($item, $pos)
	{
		$this->array_insert(&$this->_list, $item, $pos);
		//if ($pos == 0)
		//	array_unshift($this->_list, $item); 
		//else
		//	array_splice($this->_list, $pos, 0, $item);
	}
	
	public function addList (ArrayList $list)
	{
		$count = $list->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $list->get($i);
			$this->add ($item);		
		}
	}
	
	public function size ()
	{
		return Count ($this->_list);
	}
	
	public function isEmpty ()
	{
		return ($this->size() == 0);
	}
	
	public function get ($index)
	{
		return $this->_list[$index];
	}

	public function getItems ()
	{
		return $this->_list;
	}
	
	public function setItems ($array)
	{
		$this->_list = $array;
	}
	
	public function remove ($index)
	{
		unset($this->_list[$index]);
	}
	
	public function sort ($flag)
	{
		sort ($this->_list, $flag);
	}
	
	public function find ($search, $callbackFunc)
	{
		foreach ($this->_list as $item)
		{
			$ret = call_user_func($callbackFunc, $item, $search);
			if ($ret)
				return $item;	
		}
		
		return null;
	}
	
	public function clear ()
	{
		unset ($this->_list);
		$this->_list = array();
	} 
	
	
	private function array_insert(&$array, $value, $offset)
	{
	    if (is_array($array)) {
	        $array  = array_values($array);
	        $offset = intval($offset);
	        if ($offset < 0 || $offset >= count($array)) {
	            array_push($array, $value);
	        } elseif ($offset == 0) {
	            array_unshift($array, $value);
	        } else {
	            $temp  = array_slice($array, 0, $offset);
	            array_push($temp, $value);
	            $array = array_slice($array, $offset);
	            $array = array_merge($temp, $array);
	        }
	    } else {
	        $array = array($value);
	    }
	    return count($array);
	}
	
}
?>
