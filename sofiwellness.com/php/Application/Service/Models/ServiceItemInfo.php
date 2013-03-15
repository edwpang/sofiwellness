<?php
/*
 * $Id:$
 * FILE:ServiceItemInfo.php
 * CREATE: Jun 21, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class ServiceItemInfo
{
	private $_id = 0;
	private $_name;
	private $_title;
	private $_imageId = 0;
	private $_imageRef = null;
	private $_description = null;
	private $_language = null;
	private $_listOrder = 0;
	private $_status = 0;
	private $_createTime = null;
	
	public function setId ($val)
	{
		$this->_id = $val;
	}
	
	public function getId ()
	{
		return $this->_id;
	}
	
	public function setName ($val)
	{
		$this->_name = $val;
	}
	
	public function getName ()
	{
		return $this->_name;
	}

	public function setTitle ($val)
	{
		$this->_title = $val;
	}
	
	public function getTitle ()
	{
		return $this->_title;
	}
	public function setImageId ($val)
	{
		$this->_imageId = $val;
	}
	
	public function getImageId ()
	{
		return $this->_imageId;
	}
	public function setImageRef ($val)
	{
		$this->_imageRef = $val;
	}
	
	public function getImageRef ()
	{
		return $this->_imageRef;
	}
	public function setDescription ($val)
	{
		$this->_description = $val;
	}
	
	public function getDescription ()
	{
		return $this->_description;
	}
	public function setLanguage ($val)
	{
		$this->_language = $val;
	}
	
	public function getLanguage ()
	{
		return $this->_language;
	}
	
	public function setListOrder ($val)
	{
		$this->_listOrder = $val;
	}
	
	public function getListOrder ()
	{
		return $this->_listOrder;
	}

	public function setStatus ($val)
	{
		$this->_status = $val;
	}
	
	public function getStatus ()
	{
		return $this->_status;
	}
	
	public function setCreateTime ($val)
	{
		$this->_createTime = $val;
	}
	
	public function getCreateTime ()
	{
		return $this->_createTime;
	}
}
?>