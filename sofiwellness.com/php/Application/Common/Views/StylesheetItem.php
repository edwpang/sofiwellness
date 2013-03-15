<?php
/*
 * Created on Sep 7, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
require_once 'IHeadItem.php';

class StylesheetItem extends IHeadItem
{
	private $_includeFile;
	private $_text;
		
	public function getType ()
	{
		return parent::$TYPE_STYLESHEET;
	}
	
	public function setInclude ($inc)
	{
		$this->_includeFile = $inc;
	}
	
	public function setIncludeHref ($fileHref, $media)
	{
		$inc = '<link rel="stylesheet" type="text/css" href="';
		$inc .= $fileHref;
		if ($media != null)
		{
			$inc .= '" media="';
			$inc .= $media;
			$inc .= '" />';
		}
		else
			$inc .=	'" media="all" />';
		
		$this->_includeFile = $inc;
	}
	
	public function getInclude ()
	{
		return $this->_includeFile;
	}
	
	public function setText ($text)
	{
		$this->_text = $text;
	}
	
	public function getText ()
	{
		return $this->_text;
	}
} 
 
?>
