<?php
/*
 * $Id: AlertBox.php,v 1.2 2009/10/04 00:06:34 gorsen Exp $
 * FILE:AlertBox.php
 * CREATE: Dec 19, 2008
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AbstractUiComponent.php';

class AlertBox extends AbstractUiComponent
{
	private $_id = GlobalConstants::SHOW_MESSAGE;
	private $_message = null;
	private $_style = 'width:60%;';
	private $_bold = null;
	private $_cssClass = 'alert';
	
	public function setMessage ($msg)
	{
		$this->_message = $msg;
	}
	
	public function setId ($id)
	{
		$this->_id = $id;
	}
	
	public function setClass ($val)
	{
		$this->_cssClass = $val;	
	}
	public function setStyle ($style)
	{
		$this->_style = $style;
	}
	
	public function setFontBold ($bBold)
	{
		if ($bBold)
			$this->_bold = 'font-weight:700;';
		else
			$this->_bold = null;	
	}
	
	protected function doWrite (HtmlWriter $writer)
	{
		if ($this->_message == null)
			return;
		$style = $this->_style;
		if ($this->_bold != null)
			$style .= $this->_bold;
		$writer->write ('<div id="'.$this->_id .'" class="' .$this->_cssClass .'" style="' .$style .'">');
		$writer->write ($this->_message);
		$writer->write ('</div>');
	}
	
}
?>