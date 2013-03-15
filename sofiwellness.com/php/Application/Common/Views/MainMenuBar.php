<?php
/*
 $id:$
 * FILE:MainMenuBar.php
 * CREATE:Jun 23, 2008
 * BY:ghuang
 * 
 * NOTE:
 */
require_once 'AbstractUiComponent.php';
require_once APP_COMMON_DIR .'/Models/Item.php';
require_once APP_COMMON_DIR .'/Models/ItemList.php';
require_once APP_COMMON_DIR .'/Models/ItemTypes.php';

 
class MainMenuBar extends AbstractUiComponent
{
	private $_items = null;	//menu item
	private $_styleClass = 'mainMenu';
	private $_id = null;
	
	public function setId ($id)
	{
		$this->_id = $id;
	}
	
	public function setItems ($itemList)
	{
		$this->_items = $itemList;
	}
	
	public function setStyleClass ($styleClass)
	{
		$this->_styleClass = $styleClass;
	}
	
	protected function doWrite (HtmlWriter $writer)
	{
		$writer->write ('<div class="' .$this->_styleClass .'" ');
		if ($this->_id != null)
			$writer->write (' id="'.$this->_id .'"');
		if (parent::getStyle() != null)
			$writer->write (' style="'.parent::getStyle() .'"');
		$writer->write ('>');
		$writer->write ('<ul>');
		$count = $this->_items->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $this->_items->get($i);
			$name = $item->getName ();
			$href = $item->getHref ();
			$type = $item->getType();
			
			$writer->write ('<li>');
			$this->writeLink ($writer, $name, $href, $item->getSelected());
			if ($type == ItemTypes::DROPDOWN_MENU)
			{
				$writer->write('<!--[if IE 7]><!--></a><!--<![endif]-->');
				$writer->write ('<table><tr><td><ul>');
				$subItems = $item->getProperty (ItemTypes::DROPDOWN_MENU);
				$num = $subItems->size(); //it is itemList
				for ($j = 0; $j < $num; $j++)
				{
					$subItem = $subItems->get ($j);
					$subName = $subItem->getName ();
					$subHref = $subItem->getHref();
					$writer->write ('<li>');
					$this->writeLink ($writer, $subName, $subHref, false);
					$writer->write('</a>');
					$writer->write ('</li>');
				}
				$writer->write ('</ul></td></tr></table>');
				$writer->write('<!--[if lte IE 6]></a><![endif]-->');
			}
			else
			{
				$writer->write ('</a>');
			}
			
			$writer->write ('</li>');
		}
		
		
		$writer->write ('</ul>');
		$writer->write ('</div>');
	}
	
	
	private function writeLink ($writer, $name, $href, $isSelect)
	{
		$writer->write('<a');
		if ($isSelect)
			$writer->write(' id="current"');
		$writer->write(' href="' .$href .'"');
		$writer->write ('>');
		$writer->write($name);
	}
}
?>