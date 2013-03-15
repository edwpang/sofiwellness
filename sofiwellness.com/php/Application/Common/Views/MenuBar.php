<?php
/*
 * Created on Jan 12, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


require_once 'AbstractUiComponent.php';

class MenuBar extends AbstractUiComponent
{
	private $_menuItems;
	private $_selItem;
	private $_cssId;
	private $_itemList = null;

	function setMenuItem ($menuItems, $selectedItem)
	{
	 	$this->_menuItems = $menuItems;
	 	$this->_selItem = $selectedItem;
	}

	function setStyleId ($styleId)
	{
		$this->_cssId = $styleId;
	}
	
	public function setItems ($itemList)
	{
		$this->_itemList = $itemList;
	}

	function create0 ()
	{

		echo "<div id='" . $this->_cssId . "'>";
		echo "<ul>";

		while(list($key, $val) = each($this->_menuItems))
		{
			if ($key == $this->_selItem)
				echo "<li id=\"current\"><a href=\" " . $val. "\" title=\"" . $key .  "\">" . $key . "</a></li>";
			else
				echo "<li><a href=\" " . $val. "\" title=\"" . $key .  "\">" . $key . "</a></li>";
  		}

		echo "</ul>";
		echo "</div>";		
	}
	
	protected function doWrite (HtmlWriter $writer)
	{
		if ($this->_itemList != null)
			$this->createByItemList ($writer);
		else
			$this->createByArray($writer);
	}
	
	private function createByItemList (HtmlWriter $writer)
	{
		$writer->write("<div id='" . $this->_cssId . "'>");
		$writer->write("<ul>");
		$count = $this->_itemList->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $this->_itemList->get($i);
			$name =$item->getName();
			$href = $item->getHref ();
			$type = $item->getType();
			if ($item->getSelected())
				$writer->write('<li id="current">');
			else
				$writer->write ('<li>');
			if ($type != null && $type == ItemTypes::DROPDOWN_MENU)
			{
				$html = $item->getProperty(ItemTypes::DROPDOWN_MENU);
				$writer->write ($html);
			}
			else
			{
				$writer->write('<a href="' . $href. '" title="' . $name .  '">'  . $name . '</a>');
				
			}
			$writer->write('</li>');
			
		}
		$writer->write("</ul>");
		$writer->write("</div>");		
	}
	
	private function createByArray (HtmlWriter $writer)
	{
		Log::debug ('createByArray');
		$writer->write("<div id='" . $this->_cssId . "'>");
		$writer->write("<ul>");

		while(list($key, $val) = each($this->_menuItems))
		{
			if ($key == $this->_selItem)
				$writer->write( "<li id=\"current\"><a href=\" " . $val. "\" title=\"" . $key .  "\">" . $key . "</a></li>");
			else
				$writer->write("<li><a href=\" " . $val. "\" title=\"" . $key .  "\">" . $key . "</a></li>");
  		}

		$writer->write("</ul>");
		$writer->write("</div>");		
	}
}

?>
