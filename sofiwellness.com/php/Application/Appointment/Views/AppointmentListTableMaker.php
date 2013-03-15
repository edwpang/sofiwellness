<?php
/*
 * $Id: ReminderListTableMaker.php,v 1.1 2009/02/26 19:56:23 gorsen Exp $
 * FILE:ReminderListTableMaker.php
 * CREATE: Feb 23, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class ReminderListTableMaker extends AbstractUiComponent
{
	private $_id = 'reminder_list_table';
	private $_list;   //the ItemList contains item
	private $_className = 'msglist';
	private $_title = null;
	private $_totalRows = 0;  //if set, then will have total rows even data is less
	private $_checkboxArrayName = 'msgSelected';
	//private $_listType = MessageListTypes::LIST_INBOX;
	private $_onClickFunc = null;
	//private $_updateDivId = MessageConstants::MESSAGE_CONTENT_DIVID;

	private $_url = null;
	private $_style = null;

	public function __construct ()
	{
	}

	public function setList ($listItemSet)
	{
		$this->_list = $listItemSet;
	}

	public function setListType ($type)
	{
		$this->_listType = $type;
	}

	public function setTitle ($title)
	{
		$this->_title = $title;
	}
	
	public function setStyle ($style)
	{
		$this->_style= $style;	
	}

	public function setTableClass ($tableClass)
	{
		$this->_className = $tableClass;
	}

	public function setTotalRows ($totalRows)
	{
		$this->_totalRows = $totalRows;
	}

	public function setOnClickFunc ($funcName)
	{
		$this->_onClickFunc = $funcName;
	}

	public function setAjaxUpdateDivId ($id)
	{
		$this->_updateDivId = $id;
	}
	
	public function setUrl ($url)
	{
		$this->_url = $url;	
	}

	protected function doWrite (HtmlWriter $writer)
	{
		
		if ($this->_style != null)
			$writer->write ('<div id="' .$this->_id .'" class="' .$this->_className  .'" style="'.$this->_style.'">');		
		else
			$writer->write ('<div id="' .$this->_id .'" class="' .$this->_className  .'">');

		if ($this->_title != null)
			$writer->write ('<h3>' .$this->_title .'</h3>');

		$writer->write ('<ul>');

		$this->writeList ($writer);
		$emptyRows = $this->_totalRows;
		if ($this->_list != null)
			$emptyRows = $this->_totalRows - $this->_list->size();
			
		$this->writeEmptyRows ($writer, $emptyRows);

		$writer->write ('</ul>');
		$writer->write ('</div>');
	}

	///////////////////////////////////////////////////////////
	// private functions
	private function writeList (HtmlWriter $writer)
	{
		//Log::debug ('ReminderListTableMaker::writeList');
		if ($this->_list == null || $this->_list->size() == 0)
			return;

		$count = $this->_list->size();
		for ($i = 0; $i < $count; $i++)
		{
			if ($i % 2 == 0) //if number is odd
				$odd = true;
			else
				$odd = false;

			$item = $this->_list->get ($i);
			$subject = $item->getName();
			$dueDate = $item->getProperty(ReminderConstants::DUE_DATE_LOCAL);
			$id = $item->getId();
			$href = $this->_url .'&id=' .$id;
			$this->writeItemRow ($writer, $id,$subject, $dueDate,  $href, $odd);

		}
	}

	private function writeEmptyRows (HtmlWriter $writer, $rows)
	{
		$rows = $rows * 2;
		for ($i = 0; $i < $rows; $i++)
		{
			$writer->write ('<li class="empty"/>');
		}
	}

	private function writeItemRow ($writer, $id, $subject, $dueDate, $href, $odd)
	{
		if ($subject == null)
			$subject = '&nbsp;';
		if ($dueDate == null)
			$dueDate = '&nbsp; ';

		$writer->write ('<li id="' .$id .'" class="reminder"');
		$writer->write('>');

		$onFunc = null;
		if ($this->_onClickFunc != null)
			$onFunc = $this->getOnClickFunc ($href, $id);

		//From
		if ($onFunc != null)
		{
			$writer->write('<a href="#" onClick="' .$onFunc .'">');
		}
		else if ($href != null)
			$writer->write('<a href="' . $href .'">');

		$writer->write($subject);

		$writer->write('</a>');

		//Subject
		$writer->write ('<span class="msgsubj">');
		$writer->write( $dueDate);
		$writer->write('</span>');

		$writer->write ('</li>');
	}

	private function getOnClickFunc ($href, $id)
	{
		$onFunc = $this->_onClickFunc;
		$onFunc .= '(' .$id .', \''.$this->_updateDivId .'\', \''.$href .'\');return false;';
		return $onFunc;
	}
}
?>