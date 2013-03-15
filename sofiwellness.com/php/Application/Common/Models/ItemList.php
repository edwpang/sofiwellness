<?php
/*
 * Created on Aug 27, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
require_once 'ArrayList.php'; 
 
class ItemList extends ArrayList
{
	private $_id;
	private $_name; //list name
	private $_ref;
	private $_time;
	private $_desc;
	private $_properties = null;
	
	public function __construct ()
	{
		
	}
	
	public function setId ($id)
	{
		$this->_id = $id;
	}
	
	public function getId ()
	{
		return $this->_id;
	}
	
	public function setName ($name)
	{
		$this->_name = $name;
	}
	
	public function getName ()
	{
		return $this->_name;
	}
	
	public function setRef ($ref)
	{
		$this->_ref = $ref;
	}
	
	public function getRef ()
	{
		return $this->_ref;
	}
	
	public function setTime ($time)
	{
		$this->_time = $time;
	}
	
	public function getTime ()
	{
		return $this->_time;
	}
	
	public function setDescription ($desc)
	{
		$this->_desc = $desc;
	}
	
	public function getDescription ()
	{
		return $this->_desc;
	}
	
		//set properties
	public function setProperty ($name, $value)
	{
		if ($this->_properties == null)
			$this->_properties = array();
		$this->_properties[$name] = $value;
	}
	
	public function getProperty ($name)
	{
		if ($this->_properties != null)
			return $this->_properties[$name];
		else
			return null;
	}
	
	public function getProperties ()
	{
		return $this->_properties;
	}
	
	public function findByName ($name)
	{
		$count = $this->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $this->_list[$i];
			if ($item->getName() == $name)
				return $item;
		}
		return null;
	}
	
	public function findById ($id)
	{
		$count = $this->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $this->_list[$i];
			if ($item->getId() == $id)
				return $item;
		}
		return null;	
	}
	
	public function findByProperty ($propName, $propValue)
	{
		$count = $this->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $this->_list[$i];
			$val = $this->getProperty ($propName);
			if ($val != null && $val == $propValue)
				return $item;
		}
		return null;	
	}
	
	public function setItems ($items)
	{
		$this->_list = $items;
	}
	
	public function getItems ()
	{
		return $this->_list;
	}
		
	public function addItems ($itemList)
	{
		$count = $itemList->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $itemList->get($i);	
			parent::add ($item);
		}	
	}
	
	public function sortBy ($callbackFunc)
	{
		usort($this->_list, $callbackFunc);	
	}
	
	public function clear ()
	{
		unset ($this->_list);
	}
	
	public function copyAttrs (ItemList $itemListSrc)
	{
		$this->_id = $itemListSrc->getId();
		$this->_name = $itemListSrc->getName();
		$this->_ref = $itemListSrc->getRef();
		$this->_time = $itemListSrc->getTime();
		$this->_desc = $itemListSrc->getDescription();
		
		$this->_properties = $itemListSrc->getProperties();
	}
	
	//note offset start from 0
	public function slice ($offset, $length)
	{
		$list = array_slice ($this->_list, $offset, $length);
		$newList = new ItemList();
		$newList->copyAttrs($this);
		$newList->setItems ($list);
		
		return $newList;
	}	
	
	public function addItemByName ($name)
	{
		$item = new Item();
		$item->setName ($name);
		$this->add ($item);	
	}
}

?>
