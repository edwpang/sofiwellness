<?php
/*
 * $Id:$
 * FILE:VerticalImgMenu.php
 * CREATE: May 28, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class VerticalImgMenu extends AbstractUiComponent
{
	const CSS_CLASS = 'vmenubar';
	
	private $_list = null;  //ItemList for the menu item
	private $_defImage = null;
	
	public function setItems ($itemList)
	{
		$this->_list = $itemList;
	}
	
	public function setDefImage ($defImage)
	{
		$this->_defImage = $defImage;
	}
	
	protected function doWrite (HtmlWriter $writer)
	{      
		 $cls = parent::getStyleClass();
		 if ($cls == null)
		 	$cls = VerticalImgMenu::CSS_CLASS;
		
		 if ($this->_list == null || $this->_list->size() == 0)
		 	return;
		 	
		 $writer->write ('<div class="' .$cls .'"');
		 if (parent::getStyle() != null)
		 	$writer->write (' style="'.parent::getStyle() .'"');
		 $writer->write ('>');
		 $writer->write ('<ul>');
		 
		 $count = $this->_list->size();
		 for ($i = 0; $i < $count; $i++)
		 {
		 	$item = $this->_list->get($i);
		 	if ($item->isSelected())
		 		$writer->write ('<li class="select">');
		 	else
		 		$writer->write ('<li>');	
		 	$href = $item->getHref();
		 	$writer->write ('<a href="'.$href .'">');
		 	$image = $item->getImage();
		 	if ($image == null && $this->_defImage != null)
				$image = $this->_defImage;		 	
		 	if ($image != null)
		 	{
			 	$tooltip = $item->getTooltip();
			 	if ($tooltip == null)
			 		$tooltip = $item->getName();
			 	$imgStyle = null;
			 	$width = $item->getProperty ('image_width');
			 	$height = $item->getProperty('image_height');
			 	if ($width != null && $height != null)
			 		$imgStyle = 'width:' .$width .'px;height:' .$height.'px';
			 		
			 	$writer->write ('<img src="'.$image .'"');
			 	if ($imgStyle != null)
			 		$writer->write (' style="'.$imgStyle.'"');
			 	$writer->write (' alt="'.$tooltip .'"/>');
		 	}
		 	$writer->write ('<p>'.$item->getName().'</p>');
		 	$writer->write ('</a>');
		 	$writer->write ('</li>');
		 }
		 
		 $writer->write ('</ul>');
		 $writer->write ('</div>');
	}	
}
?>