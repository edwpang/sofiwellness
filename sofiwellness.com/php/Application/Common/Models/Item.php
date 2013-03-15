<?php
/*
 * Created on May 14, 2007
 *
 * Item for using in the list
 */

class Item
{
	private $_id;
	private $_name;
	private $_value;
	private $_defaultValue = null;
	private $_type;  //type: title, item
	private $_href;
	private $_tooltip = null;
	private $_image;
	private $_desc;
	private $_class;  //style class
	private $_onClick = null; //onclick event
	private $_selected = false;
	private $_properties = null;
	private $_subItemList = null;

	public function Item ()
	{
	}
	
	public function __construct ()
	{
		$this->_properties = array();
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
	
	public function setValue ($value)
	{
		$this->_value = $value;
	}
	
	public function getValue ()
	{
		return $this->_value;
	}

	public function setDefaultValue ($value)
	{
		$this->_defaultValue = $value;
	}
	
	public function getDefaultValue ()
	{
		return $this->_defaultValue;
	}


	public function setType ($type)
	{
		$this->_type = $type;
	}
	
	public function getType ()
	{
		return $this->_type;
	}

	public function isTitle ()
	{
		$bTitle = false;
		if ($this->_type == 'title')
			$bTitle = true;
		return $bTitle;
	}

	public function setHref ($href)
	{
		$this->_href = $href;
	}

	public function getHref ()
	{
		return $this->_href;
	}
	
	public function setOnClick ($onClick)
	{
		$this->_onClick = $onClick;
	}

	public function getOnClick ()
	{
		return $this->_onClick;
	}


	public function setTooltip ($tip)
	{
		$this->_tooltip = $tip;
	}

	public function setMultilineTooltip ($lines)
	{
		$buf = '';
		$n = count ($lines);
		for ($i = 0; $i < $n; $i++)
		{
			if ($i > 0)
				$buf .= '<br>';
			$buf .= $lines[$i];
		}

		$this->setTooltip ($buf);
	}

	public function getTooltip ()
	{
		return $this->_tooltip;
	}

	public function setImage ($image)
	{
		$this->_image = $image;
	}

	public function getImage ()
	{
		return $this->_image;
	}

	public function setDesc ($desc)
	{
		$this->_desc = $desc;
	}

	public function getDesc ()
	{
		return $this->_desc;
	}
	
	public function setClass ($cls)
	{
		$this->_class = $cls;
	}
	
	public function getClass ()
	{
		return $this->_class;
	}
	
	public function setSelected ($isSelected)
	{
		$this->_selected = $isSelected;
	}
	
	public function getSelected ()
	{
		return $this->_selected;
	}
	public function isSelected ()
	{
		return $this->_selected;
	}
	
	//set properties
	public function setProperty ($name, $value)
	{
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
	
	public function setSubItemList (ItemList $subItems)
	{
		$this->_subItemList = $subItems;
	}
	
	public function getSubItemList ()
	{
		return $this->_subItemList;
	}
	
	public function addToSubItemList ($item)
	{
		if ($this->_subItemList == null)
			$this->_subItemList = new ItemList ();
		$this->_subItemList->add($item);
	}

}

?>
