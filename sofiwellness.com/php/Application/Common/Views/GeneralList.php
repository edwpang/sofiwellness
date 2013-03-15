<?php
/*
 * Created on Jan 11, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class GeneralList extends AbstractUiComponent
{
	private $_styleClass = "widgetGenlist";
	private $_emptyHeight = 150;
	private $_linkClass = null;
	private $_itemList = null;
	private $_id = 'general-list';
	
	public function _construct ()
	{
	
	}
	
	public function setId ($id)
	{
		$this->_id = $id;
	}
	
	//for <a  class="cls">
	public function setLinkClass ($cls)
	{
		$this->_linkClass = $cls;	
	}
	
	public function setStyleClass ($styleClass)
	{
		$this->_styleClass = $styleClass;
	}
	
	public function setEmptyHeight ($height)
	{
		$this->_emptyHeight = $height;
	}
	
	public function setItems ($itemList)
	{
		$this->_itemList = $itemList;	
	}
	
	protected function doWrite (HtmlWriter $writer)
	{
		//get item list
		$items = $this->_itemList;
		if ($items == null || $items->size() == 0)
			Log::debug('items = null or empty');

		//write out div block
		$writer->write ('<div id="' .$this->_id.'" ');
		$writer->write ('class="' .$this->_styleClass .'" ');
		
		//add style
		$style = $this->getStyle();
		if (strlen($style) > 1)
		{
			if ($items == null || $items->size() < 4)
				$style .= '; height:' .$this->_emptyHeight;
			$writer->write(' style="' . $style .'" ');
		}
		else 
		{
			if ($items == null || $items->size() < 4)
				$writer->write(' style="height:' . $this->_emptyHeight .'" ');
		}
		
		$writer->write('>');
				
		$writer->write('<ul>');
		if ($items != null)
		{
			$firstItem = $items->get(0);	
			$tipMaker = new DynamicTooltip ();
			if ($this->_linkClass != null)
				$tipMaker->setTagClass ($this->_linkClass);
			$count = $items->size();
			for ($i = 0; $i < $count; $i++)
			{
				$item = $items->get ($i);
				if ($item->getSelected())
					$writer->write('<li class="selected">');
				else
					$writer->write('<li>');
				$href = $item->getHref();
				if ($href != null)
					$writer->write ('<a href="'.$href .'" >');
				$desc = $item->getDesc();
				$writer->write($desc);				
				if ($href != null)
					$writer->write ('</a>');
				$writer->write('</li>');
			}
		}	

		$writer->write ('</ul>');
		//if more item add pagination here or just simple more...
		$writer->write ('</div>');	
	}

} 

?>
