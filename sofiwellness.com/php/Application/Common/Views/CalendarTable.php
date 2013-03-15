<?php
/*
 * $Id: CalendarTable.php,v 1.2 2009/05/21 19:43:13 gorsen Exp $
 * FILE:CalendarTable.php
 * CREATE: Feb 19, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */

require_once 'DynamicTooltip.php';
require_once APP_COMMON_DIR .'/Models/ItemList.php';
require_once APP_COMMON_DIR .'/Models/Item.php';
require_once APP_COMMON_DIR .'/Models/ItemTypes.php';

class CalendarTable extends AbstractUiComponent
{
	private $_styleId = 'cd_month';
	private $_hasTitle = true;
	private $_baseUrl;
	private $_editUrl;
	private $_year;
	private $_month;
	private $_tipMaker;
	private $_itemList = null; //reminder data
	private $_maxTextLineLength = 18;
	private $_lockCallbackFunc = null;
	
	private $_statement = 'To make or change the appointment, click at the day cell of the table.';
	
    private $_monthNames = array("January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December");
	private $_dayNames = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    private $_daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		
	public function setYear($year)
	{
		$this->_year = $year;	
	}
	
	public function setMonth ($month)
	{
		$this->_month = $month;	
	}
	
	public function setStyleId ($styleId)
	{
		$this->_styleId = $styleId;
	}
	
	public function setHasTitle ($hasTitle)
	{
		$this->_hasTitle = $hasTitle;	
	}
	
	public function setBaseUrl ($url)
	{
		$this->_baseUrl = $url;	
	}
	
	public function setEditUrl ($url)
	{
		$this->_editUrl = $url;	
	}
	public function getDayNames ()
	{
		return $this->_dayNames;	
	}
	
	public function setItemList ($itemList)
	{
		$this->_itemList = $itemList;	
	}
	
	public function setLockCallbackFunc ($callbackFunc)
	{
		$this->_lockCallbackFunc = $callbackFunc;
	}
    
    //    Calculate the number of days in a month, taking into account leap years.
    public function getDaysInMonth($month, $year)
    {
        if ($month < 1 || $month > 12)
        {
            return 0;
        }
   
        $d = $this->_daysInMonth[$month - 1];
   
        if ($month == 2)
        {
            // Check for leap year
            if ($year%4 == 0)
            {
                if ($year%100 == 0)
                {
                    if ($year%400 == 0)
                    {
                        $d = 29;
                    }
                }
                else
                {
                    $d = 29;
                }
            }
        }
    
        return $d;
    }

	public static function getTagBkJsFunc ()
	{
		
		$highlightFunc = "function tagBk(cell, highLight)
		{
		    if (highLight)
		    {
		      cell.style.backgroundColor = '#ffc';
		      cell.style.cursor='hand';
		    }
		    else
		    {
		      cell.style.backgroundColor = '#fff';
		    }
		}";	
		return $highlightFunc;	
	}

	public static function getCellClickFunc ()
	{
		$clickFunc = ' function doNav(url){document.location.href = url;}';
		return $clickFunc;
	}
	
	protected function doWrite (HtmlWriter $writer)
	{       
		$this->_tipMaker = new DynamicTooltip();
		
		if ($this->_month == null || $this->_year == null)
		{
			$now = getdate(time());	
			$this->_month = $now['mon'];
			$this->_year = $now['year'];
		}
		
		$a = $this->adjustDate($this->_month, $this->_year);
        $month = $a[0];
        $year = $a[1];        
        
    	//$daysInMonth = $this->getDaysInMonth($month, $year);
    	$date = getdate(mktime(12, 0, 0, $month, 1, $year));
    	
    	$first = $date["wday"];
    	$startDay = 1 - $first;
    	while ($startDay > 1)
    	{
    	    $startDay -= 7;
    	}
    	$today = getdate(time());
    	$date_today = $today["mday"];
    	$mon_today = $today["mon"];
    	if ($month != $mon_today)
    		$date_today = null;
    		
    	$prev = $this->adjustDate($month - 1, $year);
    	$next = $this->adjustDate($month + 1, $year);
    	
    	$prevMonthHref = $this->getCalendarMonthLineParams($prev[0], $prev[1]);
    	$nextMonthHref = $this->getCalendarMonthLineParams($next[0], $next[1]);
 
		//$writer->write($this->_cellFunc);
		
		//now write out
		if ($this->_hasTitle)
			$this->writeTitle ($writer, $month, $year);
		
		$writer->write ('<table id="' .$this->_styleId  .'" width="90%">');
		$this->writeDaysHeader ($writer);
		$this->writeDates ($writer, $startDay, $date_today, $month, $year) ;
		
		$writer->write('</table>');
	}

	
	private function writeTitle ($writer, $month, $year)
	{
		$prevMon = $month - 1;
		$prevYear = $year;
		$nextMon = $month + 1;
		$nextYear = $year;
		if ($month == 1)
		{
			$prevMon = 12;
			$prevYear = $year - 1;
		}
		else if ($month == 12)
		{
			$nextMon = 1;
			$nextYear = $year+1;
		}
		$tip = $this->_tipMaker->createTip ($this->_monthNames[$prevMon-1]);
		$href = $this->_baseUrl .'?m='.$prevMon .'&y='.$prevYear;
		$prev = '<a href="' .$href .'" style="color:#2f5c80" onmouseover="'.$tip .'">&#9668;</a>';
		$tip = $this->_tipMaker->createTip ($this->_monthNames[$nextMon-1]);
		$href = $this->_baseUrl .'?m='.$nextMon .'&y='.$nextYear;
		$next = '<a href="' .$href .'" style="color:#2f5c80" onmouseover="'.$tip .'">&#9658;</a>';

		$monthName = $this->_monthNames[$month - 1];

		$writer->write('<p style="margin:1em 1em;">');
		$writer->write ($prev);

		$writer->write('<span class="cd_title">');
		$writer->write($monthName .' ' .$year);
		$writer->write('</span>');
		
		$writer->write($next);
		
		//write instruction
		$writer->writeSpace(10);
		$writer->write($this->_statement);
		//$writer->writeImage(GlobalConstants::getSysImage('pencil.gif'));
		$writer->write('</p>');
	}
	
	private function writePrevNextMonth($writer, $month, $year)
	{
		$prevMon = $month - 1;
		$prevYear = $year;
		$nextMon = $month + 1;
		$nextYear = $year;
		if ($month == 1)
		{
			$prevMon = 12;
			$prevYear = $year - 1;
		}
		else if ($month == 12)
		{
			$nextMon = 1;
			$nextYear = $year+1;
		}
		$tip = $this->_tipMaker->createTip ($this->_monthNames[$prevMon]);
		$href = $this->_baseUrl .'?m='.$prevMon .'&y='.$prevYear;
		$writer->write ('<a href="' .$href .'"');
		$writer->write(' onmouseover=" ' .$tip .'">');
		
		$writer->write ('&#9668;');
		$writer->write ('</a>');
	}
	
	private function writeDaysHeader ($writer)
	{
		$writer->write('<thead><tr>');
		$i = 0;
		foreach ($this->_dayNames as $name)
		{
			if ($i == 0 || $i == 6)
				$writer->write('<th class="weekend">');
			else
				$writer->write ('<th>');
			$writer->write ($name);
			$writer->write ('</th>');	
		}
		$writer->write('</tr></thead>');			
	}
	
	private function writeDates ($writer, $startDay, $today, $month, $year)
	{
		//Log::debug ('writeDates: month=' .$month .', year=' .$year .', today=' .$today);
		$daysInMonth = $this->getDaysInMonth($month, $year);
		$d = $startDay;
		$img = '<img src="'.GlobalConstants::getSysImage('pencil.gif').'">';
		$editLink = $this->_editUrl .'m=' .$month .'&y=' .$year;
		$tip = $this->_tipMaker->createTip ('Add or edit reminder');
		
		$onMouseOver = ' onMouseOver="tagBk(this, true);"';
		$onMouseOut = ' onMouseOut="tagBk(this, false);"';
		
		while ($d < $daysInMonth)
		{
			$writer->write('<tr>');
			for ($i = 0; $i < 7; $i++)
			{
				if ($d <= 0)
					$writer->write ('<td class="prev">');
				else if ($d > $daysInMonth)
					$writer->write('<td class="next">');
				else
				{
					$isLock = $this->isLock ($year, $month, $d);
					$href = $editLink .'&d='.$d;
					if ($isLock)
					{
						
						$writer->write ('<td class="lock"');
					}
					else
					{
						if ($i == 0 || $i == 6)
							$writer->write ('<td class="weekend"');
						else
							$writer->write ('<td');
							
						$writer->write (' onClick="doNav(\'' .$href .'\');"');
						$writer->write ($onMouseOver);
						$writer->write ($onMouseOut);
					}
					
					$writer->write ('>');
				}
				if ($today != null && $d == $today)
					$writer->write('<div class="date curday">');
				else
					$writer->write ('<div class="date">');	
				if ($d > 0 && $d <= $daysInMonth)
					$day = $d;
				else
					$day = $this->getDayNumber ($d, $month, $year);
				$writer->write ($day);
				//write edit image
				if ($d > 0 && $d <= $daysInMonth)
				{
					$href = $editLink .'&d='.$d;
					//$writer->write ('<a href="'.$href.'"');
					//$writer->write(' onmouseover=" ' .$tip .'"');
					//$writer->write ('>');
					//$writer->write ($img);
					//$writer->write ('</a>');
								
					//get reminder data
					$itemDate = DatetimeUtil::getDateStr ($year, $month, $day);
					$dList = $this->getItems ($itemDate);
					if ($dList != null)
					{
						$href = $editLink .'&d='.$d;
						$writer->write('<div class="day">');
						$dListCount = $dList->size();
						for ($n = 0; $n < $dListCount; $n++)
						{
							$item = $dList->get($n);
							$this->writeDataItem ($writer, $item, $href);
						}	
						$writer->write('</div>');
					}
				}
				$writer->write ('</div>');
				$d++;		
			}
			$writer->write('</tr>');
		}
	}
	
	private function writeDataItem ($writer, $item, $href)
	{
		if ($item == null)
			return;
			
		$subject = $item->getName();
		$detail = $item->getDesc();
		$time = $item->getProperty('due_date_local');
		
		$tipContent = '<strong>' .$subject .'</strong>';
		$tipContent .= '<br/><span class="italic">'.$time .'</span>';
		if ($detail != null)
			$tipContent .= '<br/>'.$detail;

		$tip = $this->_tipMaker->createTip ($tipContent);	
		$link = $href .'&id='.$item->getId();
		$name = $this->getLimitText($subject);
		$writer->write ('<p>');
		$writer->write ('<a href="'.$link.'"');
		$writer->write(' onmouseover=" ' .$tip .'"');
		$writer->write ('>');
		$writer->write ($name);
		$writer->write ('</a>');
		$writer->write('</p>');
	}
	
	
	private function getCalendarMonthLineParams ($month, $year)
	{
		return 'm='.$month .'&y=' .$year;
	}
	
	
	private function adjustDate($month, $year)
    {
        $a = array();  
        $a[0] = $month;
        $a[1] = $year;
        
        while ($a[0] > 12)
        {
            $a[0] -= 12;
            $a[1]++;
        }
        
        while ($a[0] <= 0)
        {
            $a[0] += 12;
            $a[1]--;
        }
        
        return $a;
    }	
    
    private function getDayNumber ($d, $month, $year)
    {    
    	$day = $d;
    	if ($d <= 0)
    	{
     		if ($month == 1)
     		{
     			$month = 12;
     			$year--;	
     		}
    		$dayInMonth = $this->getDaysInMonth($month, $year);
    		$day = $dayInMonth + $d;
    	}
    	else
    	{
    		$dayInMonth = $this->getDaysInMonth($month, $year);
    		$day = $d - $dayInMonth;	
    	}
    	
    	return $day;
    }
    
    //return ItemList contain Item with same date
    private function getItems ($date)
    {
    	//Log::debug ('getItems:date=' .$date);
    	if ($this->_itemList == null)
    		return null;
    		
    	$list = new ItemList();
    	$count = $this->_itemList->size();
    	for ($i = 0; $i < $count; $i++)
    	{
    		$item = $this->_itemList->get($i);
    		//Log::debug ('item date:' .$item->getProperty('date'));
    		if ($item->getProperty ('date') == $date)
    			$list->add ($item);	
    	}
    	
    	//Log::debug ('items:' .$list->size());
    	
    	if ($list->size() > 0)
    		return $list;
    	else
    		return null;
    }
    
    
    private function getLimitText($text)
    {
    	if (strlen ($text) < $this->_maxTextLineLength)
    		return $text;
    	
    	$str = substr ($text, 0, $this->_maxTextLineLength-1);
    	$str .= '...';
    	
    	
    	return $str;
    }
    
    private function isLock ($year, $month, $day)
    {
    	if($this->_lockCallbackFunc == null)
    		return false;
    		
    	$date = DatetimeUtil::getDateStr ($year, $month, $day);
    	$ret = call_user_func($this->_lockCallbackFunc, $date);	
    	return $ret;
    }
    
}
?>