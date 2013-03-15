<?php
/*
 * Created on Sep 7, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 
require_once APP_COMMON_DIR . '/Models/ArrayList.php';
require_once 'AbstractUiComponent.php';
require_once 'StylesheetItem.php';
require_once 'ScriptItem.php';
 
class HeadItemWriter extends AbstractUiComponent
{
	private $_itemList;  //it is ArrayList -old way
	
	private $_cssFolder = null;
	private $_jsFolder = null;
	private $_cssIncList = null; //ArrayList
	private $_cssDescList = null; //ArrayList;
	private $_jsIncList = null; //arrayList
	private $_jsDescList = null; //arrayList;
	private $_keywords = null;
	
	public function __construct ()
	{
	
	}
	
	public function setCssFolder ($folder)
	{
		$this->_cssFolder = $folder;
	}
	
	public function addCssInclude ($cssInc)
	{
		if ($this->_cssIncList == null)
			$this->_cssIncList = new ArrayList;
			
		$this->_cssIncList->add ($cssInc);
	}
	
	public function addCssDesc ($cssDesc)
	{
		if ($this->_cssDescList == null)
			$this->_cssDescList = new ArrayList;
		$this->_cssDescList->add ($cssDesc);
	}
	
	public function setJsFolder ($folder)
	{
		$this->_jsFolder = $folder;
	}
	
	public function addJsInclude ($jsInc)
	{
		if ($this->_jsIncList == null)
			$this->_jsIncList = new ArrayList;
			
		$this->_jsIncList->add ($jsInc);
	}	
	
	public function addJsDesc ($jsDesc)
	{
		if ($this->_jsDescList == null)
			$this->_jsDescList = new ArrayList;
			
		$this->_jsDescList->add ($jsDesc);
	}

	public function addMetaKeywords ($keywords)
	{
		$this->_keywords = $keywords;		
	}
	
	public function writeMeta ()
	{
		if ($this->_keywords != null)
		{
			$writer = parent::getWriter();	
			$writer->setBufferOutput (false);
			$writer->write ('<meta name="keywords" content="'.$this->_keywords.'"/>');
		}
	}
	
	protected function doWrite (HtmlWriter $writer)
	{
		if ($this->_cssIncList != null)
		{
			$count = $this->_cssIncList->size();
			for ($i = 0; $i < $count; $i++)
			{
				$path = null;
				if ($this->_cssFolder != null)
					$path = $this->_cssFolder .'/' .$this->_cssIncList->get($i);
				else
					$path = $this->_cssIncList->get($i);
				$writer->write('<link href="'.$path .'" rel="stylesheet" type="text/css" />');
			}
		}
		if ($this->_cssDescList != null)
		{
			$count = $this->_cssDescList->size();
			$writer->writeln('<style type="text/css">');
			for ($i = 0; $i < $count; $i++)
			{
				$writer->write($this->_cssDescList->get($i));
			}
			$writer->writeNewLine();
			$writer->write('</style>');
		}

		if ($this->_jsIncList != null)
		{
			$writer->writeNewLine();
			$count = $this->_jsIncList->size();
			for ($i = 0; $i < $count; $i++)
			{
				$path = null;
				if ($this->_jsFolder != null)
					$path = $this->_jsFolder .'/' .$this->_jsIncList->get($i);
				else
					$path = $this->_jsIncList->get($i);
				
				$writer->write('<script type="text/javascript" src="'.$path .'">');
				$writer->writeln('</script>');
			}
		}
		if ($this->_jsDescList != null)
		{
			$count = $this->_jsDescList->size();
			$writer->write('<script type="text/javascript">');
			for ($i = 0; $i < $count; $i++)
			{
				$writer->write($this->_jsDescList->get($i));
			}
			$writer->write('</script>');
		}
		
	}
	
	
	/////////////////////////////////
	//for old
	public function setItemList ($arrayList)
	{
		$this->_itemList =  $arrayList;
	}
	
	public function writeStylesheetInclude ()
	{
		$html = $this->writeOut(IHeadItem::$TYPE_STYLESHEET, true);
		return $html;
	}
	
	public function writeStylesheetText ()
	{
		$html = $this->writeOut(IHeadItem::$TYPE_STYLESHEET, false);
		return $html;
		
	}
	
	public function writeScriptInclude ()
	{
		$html = $this->writeOut(IHeadItem::$TYPE_SCRIPT, true);
		return $html;
		
	}
	
	public function writeScriptText()
	{
		$html = $this->writeOut(IHeadItem::$TYPE_SCRIPT, false);
		return $html;
		
	}
	

	public function hasStyleSheet()
	{
		if (!isset ($this->_itemList))
			return false;
			
		$num = $this->_itemList->size();
		for ($i = 0; $i < $num; $i++)
		{
			$item = $this->_itemList->get($i);
			$itemType = $item->getType();
			if (IHeadItem::$TYPE_STYLESHEET == $itemType && $item->getText() != null)
			{
				return true;
			}
		}		
		Log::debug('hasStylesheet return false');
		return false;
	}	
	public function hasScriptText()
	{
		//Log::debug('HeadItemWriter::hasScriptText');
		if (!isset ($this->_itemList))
			return false;
			
		$num = $this->_itemList->size();
		//Log::debug('number of itemList:' .$num);
		for ($i = 0; $i < $num; $i++)
		{
			$item = $this->_itemList->get($i);
			$itemType = $item->getType();
			//Log::debug('itemType:' .$itemType);
			if (IHeadItem::$TYPE_SCRIPT == $itemType)
			{
				//Log::debug('itemTxt:' . $item->getText());
				if($item->getText () != null)
					return true;
			}
		}		
		
		return false;
	}
	
	private function writeOut ($type, $isInclude)
	{
		$html = ' ';
		if (!isset ($this->_itemList))
			return $html;
			
		$num = $this->_itemList->size();
		for ($i = 0; $i < $num; $i++)
		{
			$item = $this->_itemList->get($i);
			$itemType = $item->getType();
			if ($type == $itemType)
			{
				if ($isInclude)
					$html .= $item->getInclude ();
				else 
					$html .= $item->getText ();
			}
			$html .=' ';
		}
		
		return $html;
	}
}
 
?>
