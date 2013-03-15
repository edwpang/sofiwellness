<?php
/*
 * $Id: Toolbar.php,v 1.13 2009/06/25 17:39:40 gorsen Exp $
 * FILE:Toolbar.php
 * CREATE: Sep 16, 2008
 * BY:guosheng
 *
 * NOTE:
 *
 */
require_once 'AbstractUiComponent.php';
require_once 'DynamicTooltip.php';

class Toolbar extends AbstractUiComponent
{
	private $_styleId = 'mtbar'; //old

	private $_classUl = 'mtbar';//'imgLink'; //class for ul, default is imgLink -> OLD!!!!
	private $_id = 'toolbar-mtbar'; //for div id
	private $_userName = '';
	private $_menuItems = null;   //menu items
	private $_stateItems = null;  //text show on left side
	private $_hilightFirstStateItem = true;
	private $_formName = null;
	private $_hiddenValues = null; //array contains hidden name and value pairs
	private $_useUrlAction = false;  //if true, the submit form will use url in item
	private $_searchInputHeight=20;
	private $_searchInputWidth=0;
	private $_topborderColor = null;

	public function setUserName ($userName)
	{
		$this->_userName = $userName;
	}

	public function setItems ($items)
	{
		$this->_menuItems = $items;
	}

	//states is array
	public function setStateItems ($states)
	{
		$this->_stateItems = $states;
	}

	public function setHilightFirstStateItem ($hilight)
	{
		$this->_hilightFirstStateItem = $hilight;
	}

	public function setFormName ($formName)
	{
		$this->_formName = $formName;
	}

	public function setHiddenValues ($arrayHiddens)
	{
		$this->_hiddenValues = $arrayHiddens;
	}

	public function setUseUrlAction ($useUrlAction)
	{
		$this->_useUrlAction = $useUrlAction;
	}

	public function setTopBorderColor ($color)
	{
		$this->_topborderColor= $color;
	}
	public function setStyleId ($styleId)
	{
		$this->_styleId = $styleId;
	}

	public function setSearchInputHeight ($h)
	{
		$this->_searchInputHeight = $h;
	}
	public function setSearchInputWidth ($w)
	{
		$this->_searchInputWidth = $w;
	}

	protected function doWrite (HtmlWriter $writer)
	{
		//Log::debug ('Toolbar::doWrite:'.$this->_styleId);
		$bForm = false;
		if (isset($this->_formName))
			$bForm = true;

		//for submit form script
		if ($bForm)
			$writer->write($this->getScriptFunction());

		//write <div>
		$writer->write('<div id="' .$this->_id .'" class="clear"');
		if (false === $bForm)
		{
			$writer->write(' style="width:100%;');
			if ($this->_topborderColor != null)
				$writer->write('border-top: 1px solid ' .$this->_topborderColor .';');
			$writer->write('"');
		}
		else
		{
			if ($this->_topborderColor != null)
			{
				$writer->write(' style="border-top: 1px solid ' .$this->_topborderColor .';');
				$writer->write('"');
			}		
			else
			{
				if (parent::getStyle() != null)
					$writer->write( ' style="'.parent::getStyle() .'"');				
			}		
		}
		
		
		$writer->write('>');
		$writer->write('<ul class="' .$this->_classUl .'"');
		if (parent::getStyle() != null)
			$writer->write( ' style="'.parent::getStyle() .'"');	
		$writer->write('>');			

		//write state items
		if ($this->_stateItems == null)
			$this->_stateItems = $this->getDefaultStateItems();
		$this->writeStateItems($writer);

		//write menu items
		$this->writeMenuItems ($writer);

		//write end tags
		$writer->write('</ul>');
		$writer->write('</div>');
	}


	private function writeMenuItems (HtmlWriter $writer)
	{
		$tipMaker = new DynamicTooltip ();
		//now for items
		$count =  Count ($this->_menuItems);
		for ($i = $count-1; $i >= 0; $i--)
		{
			$item = $this->_menuItems[$i];
			$type = $item->getType();
			$notForm = $item->getProperty ('not_form');
			if ($i== $count-1)
				$writer->write ('<li style="margin-right:10px;">');
			else
				$writer->write('<li>');
			if ($type != null)
			{
				if ($type == ItemTypes::DROPDOWN_MENU)
					$writer->write($item->getProperty(ItemTypes::DROPDOWN_MENU));
				else if ($type == ItemTypes::HTML_ITEM)
					$writer->write($item->getProperty(ItemTypes::HTML_ITEM));
			    else if ($type == ItemTypes::SEARCH_BOX)
					$writer->write($this->createSearchBox ($item));
			}
			else
			{
				$writer->write('<a href="' .$item->getHref() .'" ');
				//for onClick
				if ($item->getProperty(ItemTypes::HREF_ONCLICK_SCRIPT) != null)
				{
					$writer->write($item->getProperty(ItemTypes::HREF_ONCLICK_SCRIPT));
				}
				else
				{
					if ($this->_formName != null && $notForm == null)
					{
						$html = $this->getOnClickHtml ($item, $this->_useUrlAction);
						$writer->write($html);
					}
				}
				//$writer->write(' class="' .$item->getClass() .'"');
				if ($item->getTooltip() != null)
				{
					$tip = $tipMaker->createTip($item->getTooltip());
					$writer->write(' onmouseover=" ' .$tip .'"');
				}
				$writer->write('>');

				//for icon class
				$writer->write('<em class="'.$item->getClass() .'"></em>');

				//write menu name
				$writer->write('<b>');
				$writer->write($item->getName());
				$writer->write('</b>');
				$writer->write('</a>');
			}
			if ($i == ($count - 1))
				$writer->writeSpace(2);
			$writer->write('</li>');
		}
	}

	public static function getSearchResultStatusText ($searchText, $total, $pageSize=0, $pageNum=0)
	{
		//Log::debug ('getSearchResultStatusText:total:' .$total .',pageSize:' .$pageSize .', pageNum:' .$pageNum);
		if ($total > 0)
		{
			$itemStart = ($pageNum - 1) * $pageSize + 1;
			$itemEnd = $total;
			if ($pageSize > 0)
			{
				$itemEnd = $itemStart + $pageSize  - 1;
				if ($itemEnd > $total)
					$itemEnd = $total;
			}
			else
				$itemEnd = $total;
			if ($itemStart != $itemEnd)
				$resultText = 'Result <span class="strong">' .$itemStart .' - ' .$itemEnd .'</span> of ' .$total;
			else
				$resultText = 'Result <span class="strong">' .$itemStart .'</span> of ' .$total;
			$resultText .= ' for <span class="bold">' .$searchText .'</span>.';
		}
		else
		{
			$resultText = 'No results for <span class="bold">'.$searchText .'</span>.';
		}

		return $resultText;
	}


	private function writeStateItems (HtmlWriter $writer)
	{
		if ($this->_stateItems == null)
			return;
		$i = 0;
		foreach ($this->_stateItems as $item)
		{
			$writer->write('<li class="state">');
			if ($this->_hilightFirstStateItem && $i == 0)
				$writer->write ('<span class="strong">');

			$writer->write($item);
			if ($this->_hilightFirstStateItem && $i == 0)
				$writer->write ('</span>');
			$writer->write('</li>');
			$i++;
		}
	}

	/////////////////////////////////////////////////////////////////////////////
	//old
	protected function doWrite0 (HtmlWriter $writer)
	{
		//Log::debug ('Toolbar::doWrite:'.$this->_styleId);
		$bForm = false;
		if (isset($this->_formName))
			$bForm = true;

		//for submit form script
		if ($bForm)
			$writer->write($this->getScriptFunction());

		//write <div>
		$writer->write('<div id="' .$this->_styleId .'"');
		if (false === $bForm)
			$writer->write(' style="width:100%;"');
		$writer->write('>');
		$writer->write('<ul class="' .$this->_classUl .'">');

		//write state items
		if ($this->_stateItems == null)
			$this->_stateItems = $this->getDefaultStateItems();
		$this->writeStateItems($writer);

		//write menu items
		$this->writeMenuItems ($writer);

		//write end tags
		$writer->write('</ul>');
		$writer->write('</div>');
	}


	private function writeStateItems0 (HtmlWriter $writer)
	{
		if ($this->_stateItems == null)
			return;
		$i = 0;
		foreach ($this->_stateItems as $item)
		{
			$writer->write('<li class="txt_l">');
			if ($this->_hilightFirstStateItem && $i == 0)
				$writer->write ('<span class="strong">');

			$writer->write($item);
			if ($this->_hilightFirstStateItem && $i == 0)
				$writer->write ('</span>');
			$writer->write('</li>');
			$i++;
		}
	}



	private function writeMenuItems0 (HtmlWriter $writer)
	{
		$tipMaker = new DynamicTooltip ();
		//now for items
		$count =  Count ($this->_menuItems);
		for ($i = 0; $i < $count; $i++)
		{
			$item = $this->_menuItems[$i];
			$type = $item->getType();
			$notForm = $item->getProperty ('not_form');
			$writer->write('<li>');
			if ($type != null)
			{
				if ($type == ItemTypes::DROPDOWN_MENU)
					$writer->write($item->getProperty(ItemTypes::DROPDOWN_MENU));
				else if ($type == ItemTypes::HTML_ITEM)
					$writer->write($item->getProperty(ItemTypes::HTML_ITEM));
			    else if ($type == ItemTypes::SEARCH_BOX)
					$writer->write($this->createSearchBox ($item));
			}
			else
			{
				$writer->write('<a href="' .$item->getHref() .'" ');
				//for onClick
				if ($item->getProperty(ItemTypes::HREF_ONCLICK_SCRIPT) != null)
				{
					$writer->write($item->getProperty(ItemTypes::HREF_ONCLICK_SCRIPT));
				}
				else
				{
					if ($notForm == null)
					{
						$html = $this->getOnClickHtml ($item, $this->_useUrlAction);
						$writer->write($html);
					}
				}
				$writer->write(' class="' .$item->getClass() .'"');
				if ($item->getTooltip() != null)
				{
					$tip = $tipMaker->createTip($item->getTooltip());
					$writer->write(' onmouseover=" ' .$tip .'"');
				}
				$writer->write('>');
				$writer->write($item->getName());
				$writer->write('</a>');
			}
			$writer->write('</li>');
		}
	}

	private function getOnClickHtml ($item, $useUrlAction)
	{
		$html = '';
		if ($useUrlAction == true)
		{
			$html .= 'onClick="submitForm(\'' .$item->getHref() .'\', \'';
		}
		else
		{
			$html .= 'onClick="submitForm(null, \'';
		}
		$html .= $item->getName();
		$html .= '\' );return false;" ';

		return $html;
	}

 	private function getScriptFunction ()
 	{
 		$str = '<script language="Javascript">';
 		$str .= 'function submitForm(url, action)';
 		$str .= '{updateAllDOMFields(document.' .$this->_formName .');';
 		$str .= 'document.';
 		$str .= $this->_formName;
 		$str .= '.innerHTML=document.';
 		$str .= $this->_formName;
 		$str .= '.innerHTML+"<input type=hidden name=action value=\'" + action +"\'>";';
 		if ($this->_useUrlAction)
 		{
 			$str .= ' document.' . $this->_formName .'.action=url;';
 		}
 		//$str .= 'alert(action);';
 		$str .= ' document.';
 		$str .= $this->_formName;
 	    $str .= '.submit();}';
	    $str .= '</script>';

		return $str;
 	}

	private function getDefaultStateItems ()
	{
		$today = date('F j, Y');
		return array ($this->_userName, $today);
	}

	/*
	<input name="keyword_" type="text" size="25" style="color:#999;" maxlength="128" id="keyword_"
onblur="this.value = this.value || this.defaultValue; this.style.color = '#000';"
onfocus="this.value=''; this.style.color = '#000';" value="Search Term">
*/
	private function createSearchBox (Item $item)
	{
		$html = '<form name="search_box" action="' .$item->getHref().'" method="post">';
		if ($item->getSubItemList() != null)
		{
			$subList = $item->getSubItemList();
			$count = $subList->size();
			for ($i = 0; $i < $count; $i++)
			{
				$hiddenItem = $subList->get($i);
				$html .= '<input type=hidden name="' .$hiddenItem->getName() .'" value="' .$hiddenItem->getValue().'"/>';
			}
		}

		$tip = null;
		if ($item->getTooltip() != null)
		{
			$tipMaker = new DynamicTooltip();
			$tip = $tipMaker->createTip ($item->getTooltip());
		}
		else
		{
			$tipMaker = new DynamicTooltip();
			$tip = $tipMaker->createTip ('Search');
		}

		$val = $item->getValue();
		if ($val == null)
			$val = $item->getDefaultValue();
		Log::debug ('val=' .$val .', defaultValue:' .$item->getDefaultValue());
		$html .= '<input id="search" name="search" type="text" value="' .$val .'"';
		if ($item->getDefaultValue() != null || $this->_searchInputHeight != 0)
		{
			$html .= ' style="';
			if ($item->getDefaultValue () != null)
				$html .= 'color:#999;';
			if ($this->_searchInputHeight != 0)
				$html .= 'height:'.$this->_searchInputHeight.'px;';
			if ($this->_searchInputWidth > 0)
				$html .="width:" .$this->_searchInputWidth .'px;';
			$html .='"';
			Log::debug ('searchInputWidth=' .$this->_searchInputWidth);
			//Log::debug ('search:' .$html);
		}
		if ($item->getProperty (ItemTypes::SEARCH_PEOPLE) != null)
			$html .= ' class="search_p"';
		if ($tip != null)
			$html .= ' onmouseover=" ' .$tip .'"';
		if ($item->getDefaultValue() != null)
		{
			$html .= ' onblur="this.value = this.value || this.defaultValue; this.style.color = \'#000\';"';
			$html .= ' onfocus="this.value=\'\'; this.style.color = \'#000\';"';
		}

		$html .= '/>';
		//$html .= '&nbsp;<input type="submit" class="submitSearch" value="" />';
		$html .= '<input type="submit" class="submSearch" value=""';
		if ($tip != null)
		{
			$html .= ' onmouseover=" ' .$tip .'"';
		}
		else
		{
			$html .= ' onmouseover=" ' .$tip .'"';
		}
		$html .= '/>';
		$html .= '</form>';
		return $html;
	}

}
?>