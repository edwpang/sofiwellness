<?php
/*
 * Created on Feb 28, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
require_once  APP_COMMON_DIR . '/Util/HtmlWriter.php'; 
 
abstract class AbstractUiComponent
{
	private $_styleClass = null;
	private $_style = null;
	
	protected $_writer=null;
	
	abstract protected function doWrite (HtmlWriter $writer);

	public function setStyleClass ($styleClass)
	{
		$this->_styleClass = $styleClass;	
	}
	
	public function getStyleClass ()
	{
		return $this->_styleClass;
	}

	public function setStyle ($style)
	{
		$this->_style = $style;	
	}
	
	public function getStyle ()
	{
		return $this->_style;
	}

	//create the Widget, return html string
	public function create ()
	{
		if ($this->_writer == null)
			$this->_writer = new HtmlWriter();
			
		$this->_writer->setBufferOutput (true);
		$this->doWrite ($this->_writer);
		return $this->_writer->getBuffer ();
	}
	
	
	//output Widget via echo
	public function output ()
	{
		if ($this->_writer == null)
			$this->_writer = new HtmlWriter();
		$this->_writer->setBufferOutput (false);
		$this->doWrite ($this->_writer);		
	}
	
	protected function getWriter ()
	{
		if ($this->_writer == null)
			$this->_writer = new HtmlWriter();
			
		return $this->_writer;	
	}
}
?>
