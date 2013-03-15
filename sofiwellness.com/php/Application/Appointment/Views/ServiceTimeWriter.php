<?php
/*
 * $Id:$
 * FILE:ServiceTimeWriter.php
 * CREATE: May 31, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once APP_COMMON_DIR .'/Views/AbstractUiComponent.php';

class ServiceTimeWriter extends AbstractUiComponent
{
	private $_dates = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
	private $_listTime = null;
	private $_listTimeSat = null; //list time for saturday
	private $_listTimeSun = null; //list time for sunday
	private $_listOffDayNames = null;  //ArrayList list day off such as sunday
	
	private $_form = null;  //ServiceTimeForm
	
	public function setListTime($times)
	{
		$this->_listTime = $times;	
	}
	
	public function setSaturdayListTime ($list)
	{
		$this->_listTimeSat = $list;	
		//Log::debug ('sat list size:' .$this->_listTimeSat->size());
	}
	
	public function setSundayListTime($list)
	{
		$this->_listTimeSun = $list;
	}
	
	public function setWeekOffDayNames ($list)
	{
		$this->_listOffDayNames = $list;
	}
	
	public function setForm ($form)
	{
		$this->_form = $form;	
	}
	
	protected function doWrite (HtmlWriter $writer)
	{
		//Log::debug ('ServiceTimeWriter::doWrite');
		$dates = $this->_dates;
		//Log::debug (count($dates));
		$times = $this->_listTime;
		$n = $times->size() - 1;
		$lastTime = $times->get($n);
		foreach ($dates as $key => $date)
		{
			//Log::debug ('date:' .$date);
			
			$iDate = strtolower($date);
			//Log::debug ('idate:' .$iDate);
			if ($this->isOffDay ($iDate))
				continue;
				
			$checked = $this->_form->getChecked($iDate);
			$writer->write ('<div class="row">');
			$writer->write ('<label class="col_r">'.$date .':</label>');			
			$writer->write ('<span class="col_l_500">');
			
			$writer->write ('<input type="checkbox" name="'.$iDate .'" value="'.$iDate.'"' .$checked .' >');
			$writer->write ('<span class="marl2">');
			$writer->write ('<label>Start:</label>');
			$writer->write ('<select name="'.$iDate .'_start_time">');
			$st = $this->_form->getStartTime($iDate);
			$count = $times->size();
			for ($i= 0; $i < $count; $i++)
			{
				$tItem = $times->get($i);
				if ($tItem == $st)
					$writer->write('<option value="' .$tItem .'" selected>' .$tItem .'</option>');
				else
					$writer->write('<option value="' .$tItem .'">' .$tItem .'</option>');
			}
			
			$writer->write ('</select>');
			$writer->write ('</span>');
			$writer->write ('<span class="marl2">');
			$writer->write ('<label>End:</label>');
			$writer->write ('<select name="'.$iDate .'_end_time">');
			$listTime = $times;
			if ($iDate == 'saturday' && $this->_listTimeSat != null)
			{
				$listTime = $this->_listTimeSat;
				$lastTime = $listTime->get($listTime->size()-1);
			}
			else if ($iDate == 'sunday' && $this->_listTimeSun != null)
			{
				$listTime = $this->_listTimeSun;
				$lastTime = $listTime->get($listTime->size()-1);
			}	
			$st = $this->_form->getEndTime($iDate);
			if ($st == null)
				$st = $lastTime;
			
			$count = $listTime->size();
			for ($i= 0; $i < $count; $i++)
			{
				$tItem = $listTime->get($i);
				if ($tItem == $st)
					$writer->write ('<option value="' .$tItem .'" selected>' .$tItem .'</option>');
				else
					$writer->write ('<option value="' .$tItem .'">' .$tItem .'</option>');
			}
			$writer->write ('</select>');
			$writer->write ('</span>');
			
			$writer->write ('</span>');
			$writer->write ('</div>');
			
		}
	}
	
	private function isOffDay ($day)
	{
		if ($this->_listOffDayNames == null || $this->_listOffDayNames->size() == 0)
		{
			Log::debug ('isOffDay return false');
			return false;
		}	
		$names = $this->_listOffDayNames;
		$count = $names->size();
		for ($i = 0; $i < $count; $i++)
		{	
			$name = $names->get($i);
			if ($name == $day)
			{
				Log::debug ('it is offday:' .$day);
				return true;	
			}
		}
		
		return false;
	}
}
?>