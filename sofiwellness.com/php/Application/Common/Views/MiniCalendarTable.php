<?php
/*
 * $Id:$
 * FILE:MiniCalendarTable.php
 * CREATE: Jun 10, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'DynamicTooltip.php';
require_once APP_COMMON_DIR .'/Models/ItemList.php';
require_once APP_COMMON_DIR .'/Models/Item.php';
require_once APP_COMMON_DIR .'/Models/ItemTypes.php';

class MiniCalendarTable extends AbstractUiComponent
{
	private $_styleClass = 'cal';
	private $_year;
	private $_month;
	private $_day; 
	private $_baseUrl;
	private $_offDateList = null;  //ArrayList
	
    private $_monthNames = array("January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December");
	private $_dayNames = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
    private $_daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

	public function setDate ($date)
	{
		$ar = DatetimeUtil::parseDatetime ($date);
		$this->_year = $ar['year'];
		$this->_month = $ar['month'];
		$this->_day = $ar['day'];	
	}
	
	public function setYear($year)
	{
		$this->_year = $year;	
	}
	
	public function setMonth ($month)
	{
		$this->_month = $month;	
	}
	
	public function setDay ($day)
	{
		$this->_day = $day;
	}
	
	public function setBaseUrl ($url)
	{
		$this->_baseUrl = $url;	
	}
	
	public function setOffDate ($offdateList)
	{
		$this->_offDateList = $offdateList;
	}
	
	
	protected function doWrite (HtmlWriter $writer)
	{
		if ($this->_month == null || $this->_year == null)
		{
			$now = getdate(time());	
			$this->_month = $now['mon'];
			$this->_year = $now['year'];
		}
		
		$a = $this->adjustDate($this->_month, $this->_year);
        $month = $a[0];
        $year = $a[1];        
        
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
    		
		$writer->write ('<table class="' .$this->_styleClass  .'" width="90%">');

		//now write out
		$this->writeTitle ($writer, $month, $year);	
		$this->writeDaysHeader ($writer);
		$this->writeDates ($writer, $startDay, $date_today, $month, $year) ;
		
		$writer->write('</table>');		       
	}
	
	//////////////////////////////////////////////////////////////////////
	//private
	private function writeTitle ($writer, $month, $year)
	{
		$monthName = $this->_monthNames[$month - 1];

		$writer->write('<caption>');
		$writer->write($monthName .' &nbsp;&nbsp;' .$year);
		$writer->write('</caption>');
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
		$url = $this->_baseUrl .'y=' .$year .'&m=' .$month;
		
		while ($d < $daysInMonth)
		{
			$writer->write('<tr>');
			for ($i = 0; $i < 7; $i++)
			{
				$writer->write ('<td>');
				if ($d <= 0 ||$d > $daysInMonth)
				{
					$writer->write('&nbsp;');
				}
				else
				{
					if ($today != null && $d == $today)
						$writer->write('<span class="curday">');
					
					if ($d > 0 && $d <= $daysInMonth)
						$day = $d;
					else
						$day = $this->getDayNumber ($d, $month, $year);
					
					$bOff = false;	
					if ($this->_offDateList != null)
						$bOff = $this->isOffDate ($day);
					if (!$bOff)
					{
						$href = $url .'&d='.$d;
						if ($this->_day != null && $this->_day == $day)
							$writer->write('<a href="'.$href.'" class="select">');	
						else
							$writer->write('<a href="'.$href.'">');	
					}
					$writer->write ($day);
					if (!$bOff)
						$writer->write ('</a>');
					if ($today != null && $d == $today)
						$writer->write('</span>');
				}
				$d++;		
				$writer->write('</td>');
			}
			$writer->write('</tr>');
		}
	}	
	
	private function isOffDate ($date)
	{
		if ($this->_offDateList == null || $this->_offDateList->size() == 0)
			return false;
			
		$count = $this->_offDateList->size();
		for ($i = 0; $i < $count; $i++)
		{
			$d = $this->_offDateList->get($i);
			if ($d == $date)
				return true;			
		}
			
		return false;	
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
    
    //    Calculate the number of days in a month, taking into account leap years.
    private function getDaysInMonth($month, $year)
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
}
?>