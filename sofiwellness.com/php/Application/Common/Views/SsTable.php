<?php
/*
 * $Id: SsTable.php,v 1.2 2009/04/06 19:11:49 gorsen Exp $
 * FILE:SsTable.php
 * CREATE: Jan 15, 2009
 * BY:guosheng
 *
 * NOTE:
 *
 */
require_once 'AbstractUiComponent.php';
require_once APP_COMMON_DIR .'/Models/Item.php';
require_once APP_COMMON_DIR .'/Models/ItemList.php';

class SsTable extends AbstractUiComponent
{
	private $_id = null;
	private $_styleClass = 'sd';
	private $_style = null;
	private $_arrayColumnNames = null; //for mapping item property keys
	private $_caption = null;
	private $_headItemList = null;  //for head and link, property 'sort' contain value:up or down
	private $_itemList = null;  //table data
	private $_imageWidth = null;
	private $_imageHeight = null;
	private $_tipMaker = null;
	private $_enableRowId = false;
	private $_rowIdPrefix = null;
	private $_sortImageUp = null;
	private $_sortImageDown = null;


	public function setId ($id)
	{
		$this->_id = $id;
	}

	public function setStyleClass ($clsName)
	{
		$this->_styleClass = $clsName;
	}

	public function setStyle ($style)
	{
		$this->_style = $style;
	}

	public function setColumn ($arrayColumn)
	{
		$this->_arrayColumnNames = $arrayColumn;
	}

	public function setHeadItemList ($itemList)
	{
		$this->_headItemList = $itemList;
	}

	public function setItemList ($dataItemList)
	{
		$this->_itemList = $dataItemList;
	}

	public function setImageSize ($width, $height)
	{
		$this->_imageWidth = $width;
		$this->_imageHeight = $height;
	}

	public function setEnableRowId ($bEnable, $prefix=null)
	{
		$this->_enableRowId = $bEnable;
		$this->_rowIdPrefix = $prefix;
	}
	
	public function setSortImage ($upImage, $downImage)
	{
		$this->_sortImageUp = $upImage;
		$this->_sortImageDown = $downImage;	
	}

	public function writeRowHtml ($itemList, $writer=null)
	{
		//Log::debug ('writeRowHmtl');
		$bNewWriter = false;
		if ($writer == null)
		{
			$writer = new HtmlWriter();
			$writer->setBufferOutput (true);
			$bNewWriter= true;
		}
		$rowId=$itemList->getId ();
		$count = $itemList->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $itemList->get($i);
			$type = $item->getType();
			$href = $item->getHref();
			$img = $item->getImage();
			$name = $item->getName();
			$writer->write('<td>');
			//Log::debug ('name:' .$name .', type=' .$type);
			if ($type != null && $type == ItemTypes::HTML_ITEM)
			{
				$writer->write($item->getProperty(ItemTypes::HTML_ITEM));
			}
			else
			{
				if ($href != null)
				{
					$writer->write('<a href="' .$href .'"');
					if ($item->getTooltip() != null)
					{
						$tip = $this->_tipMaker->createTip($item->getTooltip());
						$writer->write(' onmouseover=" ' .$tip .'"');
					}
					$writer->write ('>');

				}
				if ($img != null)
				{
					$writer->write('<img src="' .$img .'"');
					if ($this->_imageWidth != null)
						$writer->write (' width="' .$this->_imageWidth.'"');
					if ($this->_imageHeight != null)
						$writer->write(' height="'.$this->_imageHeight .'"');
					$writer->write ('>');
				}
				if ($name != null)
					$writer->write ($name);

				if ($href != null)
					$writer->write('</a>');
			}
			$writer->write('</td>');
		}
		
		if ($bNewWriter)
			return $writer->getBuffer();
	}

	protected function doWrite (HtmlWriter $writer)
	{
		$this->_tipMaker = new DynamicTooltip ();

		$writer->write ('<table class="' .$this->_styleClass .'"');
		if ($this->_id != null)
			$writer->write (' id="' .$this->_id .'"');
		if ($this->_style != null)
			$writer->write (' style="' .$this->_style  .'"');
		$writer->write ('>');
		
		//write caption
		if ($this->_caption != null)
			$writer->write ('<caption>' .$this->_caption .'</caption>');

		//write head
		$this->writeHead ($writer);

		$writer->write ('<tbody>');
		$count = $this->_itemList->size ();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $this->_itemList->get($i);
			$this->writeRow ($writer, $item);
		}
		$writer->write('</tbody>');

		$writer->write ('</table>');
	}


	protected function writeHead ($writer)
	{
		$writer->write ('<thead>');
		$writer->write ('<tr>');

		if ($this->_headItemList != null)
		{
			$count = $this->_headItemList->size();
			for ($i = 0; $i < $count; $i++)
			{
				$item = $this->_headItemList->get($i);
				$name = $item->getName();
				$href = $item->getHref();
				$sort = $item->getProperty('sort');
				$writer->write ('<th>');
				if ($href != null)
				{
					$writer->write ('<a href="' .$item->getHref().'"');
					if ($item->getTooltip() != null)
					{
						$tip = $this->_tipMaker->createTip($item->getTooltip());
						$writer->write(' onmouseover=" ' .$tip .'"');
					}
					$writer->write ('>');
				}
				$writer->write ($name);
				if ($href != null)
				{
					if ($sort != null)
					{
						if ($this->_sortImageUp != null)
						{
							$img = $this->_sortImageDown;
							if ($sort == 'up')
								$img = $this->_sortImageUp;
							$writer->writeSpace(1);
							$writer->write('<img src="' .$img .'"/>');
						}
						else
						{
							$sortSym = '&#8595;';
							if ($sort == 'up')
								$sortSym = '&#8593;';
							$writer->writeSpace(1);
							$writer->write($sortSym);
						}
					}
					$writer->write('</a>');
				}
				$writer->write ('</th>');
			}
		}

		$writer->write('</tr>');
		$writer->write ('</thead>');
	}


	protected function writeRow ($writer, $item)
	{
		$writer->write('<tr>');
		if ($this->_enableRowId)
		{
			$rId = $item->getId ();
			if ($this->_rowIdPrefix != null)
				$rId = $this->_rowIdPrefix .$item->getId ();
			$writer->write('<div id="' .$rId .'">');
		}
		//item should contain subItemList for each column
		$itemList = $item->getSubItemList();
		$itemList->setId ($item->getId());
		if ($itemList != null)
		{
			$this->writeRowHtml($itemList, $writer);
		}

		if ($this->_enableRowId)
			$writer->write('</div>');
		$writer->write ('</tr>');
	}



}
?>
