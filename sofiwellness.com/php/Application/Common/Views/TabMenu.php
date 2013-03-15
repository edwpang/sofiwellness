<?php
/*
 * $Id:$
 * FILE:TabMenu.php
 * CREATE: May 16, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AbstractUiComponent.php';

class TabMenu extends AbstractUiComponent
{
	const CSS_CLASS_ID = 'tab-menu';
	
	private $_id = TabMenu::CSS_CLASS_ID;
	private $_list = null;
	private $_selected = null;
	private $_backgroundColor = 'transparent';//'#fff';
	
	
	public function setItems ($list)
	{
		$this->_list = $list;
	}
	
	public function setSelected ($selected)
	{
		$this->_selected = $selected;
	}
	
	public function setBackgroundColor ($color)
	{
		$this->_backgroundColor = $color;
	}
	
	
	protected function doWrite (HtmlWriter $writer)
	{
		if ($this->_list == null)
			return;
			
		$style = parent::getStyle();
		if ($this->_backgroundColor != null)
		{
			if ($style != null)
				$style .= 'background:'.$this->_backgroundColor .';';
			else
				$style = 'background:'.$this->_backgroundColor .';';
		}
		
		$writer->write ('<div id="'.$this->_id .'" ');
		if ($this->_backgroundColor != null)
		if ($style != null)
			$writer->write(' style="' .$style .'"');

		$writer->write('>');
		$writer->write('<ul>');
		$list = $this->_list;
		$count = $this->_list->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $list->get($i);
			if (($this->_selected != null &&  $this->_selected == $item->getName()) || $item->isSelected())
				$writer->write ('<li id="current">');
			else
				$writer->write ('<li>');
			$writer->write('<a href="'. $item->getHref() .'">');
			$writer->write ('<span>');
			$writer->write ($item->getName());
			$writer->write ('</span>');
			$writer->write ('</a>');
			$writer->write ('</li>');
		}	
		$writer->write('</ul>');
		$writer->write ('</div>');	
	}
	
}
?>