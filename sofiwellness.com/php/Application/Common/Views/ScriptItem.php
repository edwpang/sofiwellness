<?php
/*
 * Created on Sep 7, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once 'IHeadItem.php';

class ScriptItem extends IHeadItem
{
	private $_includeFile;
	private $_text;
		
	public function getType ()
	{
		return parent::$TYPE_SCRIPT;
	}
	
	public function setInclude ($inc)
	{
		$this->_includeFile = $inc;
	}
	
	public function getInclude ()
	{
		return $this->_includeFile;
	}
	
	public function setIncludeHref ($fileHref)
	{
		$inc = '<script type="text/javascript" src="';
		$inc .= $fileHref;
		$inc .=	'"></script>';
			
		$this->_includeFile = $inc;
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
