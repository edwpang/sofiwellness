<?php
/*
 * $Id:$
 * FILE:ScheduleWriter.php
 * CREATE: May 24, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once APP_COMMON_DIR .'/Views/AbstractUiComponent.php';

class ScheduleWriter extends AbstractUiComponent
{
	const CSS_CLASS_ID = 'schedule';
	
	private $_id = ScheduleWriter::CSS_CLASS_ID;
	private $_list = null;  //the appointment item list
	private $_selected = null;
	private $_backgroundColor = '#fff';
	private $_startHour = '8:00';
	private $_endHour = '20:00';
	private $_theDate;
	private $_resourceId = 0;
	private $_userId = 0;
	private $_columns = null; //itemlist for defined column name and id
	private $_accountType = AccountTypes::CUSTOMER;
	
	//for hold data
	private $_onTimeInfo = null;
	private $_offTimeInfo = null;
	
	public function setItems ($list)
	{
		$this->_list = $list;
	}
	
	public function setHours ($start, $end)
	{
		$this->_startHour = $start;
		$this->_endHour = $end;
	}
	
	public function setAccountType ($type)
	{
		$this->_accountType = $type;
	}
	
	public function setColumns ($itemList)
	{
		$this->_columns = $itemList;
	}
	
	//date is Y-m-d
	public function setDate ($date)
	{
		$this->_theDate = $date;
	}
	
	public function setResourceId ($who)
	{
		$this->_resourceId = $who;
	}
	
	public function setUserId ($userId)
	{
		$this->_userId = $userId;
	}
	
	protected function doWrite (HtmlWriter $writer)
	{
		Log::debug ('ScheduleWriter::doWrite');
			
		$writer->write ('<table id="'.$this->_id .'">');
		$columns = $this->_columns;
		if ($columns == null)
			$columns = $this->getDefaultHeadColumn();
		$this->writeHeader ($writer, $columns);
		$writer->write ('<tbody>');
	    $date = DatetimeUtil::getDateOnly ($this->_theDate);
	    $this->getResourceOnOffTime ($this->_resourceId, $date);
	    
		$listTime = $this->getTimeList ($this->_startHour, $this->_endHour);
		$count = $listTime->size();
		$editable = true;
		for ($i = 1; $i < $count; $i++)
		{
			$bOdd = false; 
			if ($i % 2)
				$bOdd = true;
			$startTime = $listTime->get($i-1);
			$endTime = $listTime->get($i);
			$start = DatetimeUtil::formatDatetimeStr ($startTime, DatetimeUtil::SHORT_TIME_PATTEN);
			$end = DatetimeUtil::formatDatetimeStr ($endTime, DatetimeUtil::SHORT_TIME_PATTEN);
			//Log::debug ('##startTime:' .$startTime .', start:'.$start);
			$editable = $this->isEditable ($start, $end);			
			$this->writeTableItem ($writer, $startTime, $endTime, $bOdd, $editable);
			
		}	
		$writer->write ('</tbody>');
		$writer->write ('</table>');
	}
	
	
	private function writeHeader ($writer, $columns)
	{
		$writer->write ('<thead>');
		$count = $columns->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $columns->get($i);
			if ($i == 0)
				$writer->write ('<th class="tm">');
			else
				$writer->write ('<th>');
			$writer->write ($item->getName());
			$writer->write ('</th>');
		}
		$writer->write ('</thead>');		
	}
	
	private function writeTableItem ($writer, $startTime, $endTime, $odd, $editable=true)
	{
		$sTime = strtotime ($startTime);
		$item = $this->getAppointmentItem ($startTime);
		$isRes = $this->isUserResource ();
		$resEdit = false;
		$tdCls = 'lock';
		if ($isRes && $item!= null)
		{
			$tdCls = 'booked';
			$resEdit = true;
		}
		
		if ($odd)
			$writer->write('<tr class="odd">');
		else
			$writer->write('<tr>');	
		$writer->write ('<th>');
		$writer->write ($startTime .' - ' .$endTime);
		$writer->write ('</th>');
		if ($editable)
			$writer->write ('<td>');
		else 
			$writer->write ('<td class="'.$tdCls .'">');
		$href = '/appointment/edit/book?date=' .urlencode($this->_theDate) .'&t='.urlencode($startTime);
		$href .= '&res=' .$this->_resourceId;
		if ($item != null)
			$href .= '&aid=' .$item->getId();	
		if ($editable || $resEdit)
			$writer->write ('<a href="'.$href .'">');
		if ($item != null)
			$writer->write($item->getName());
		if ($editable || $resEdit)
			$writer->write ('</a>');
		$writer->write('</td>');
		$writer->write ('</tr>');
	}
	
	private function getTimeList ($startTime, $endTime)
	{
		$list = new ArrayList ();
		
		//every half hour
		$etime = date_parse($endTime);
		$eh = $etime['hour'];
		$em = $etime['minute'];
		$stime = date_parse($startTime);
		$h = $stime['hour'];
		$m = $stime['minute'];
		
		while (true)
		{
			$time = date ('g:i a' ,mktime ($h, $m));
			//Log::debug ('h:' .$h .', m:' .$m .'    time=' .$time);
			$list->add ($time);
			$m += 30;
			if ($m == 60)
			{
				$m = 0;
				$h++;
			}
			
			if ($h > $eh || ($h == $eh && $m > $em))
				break;
		}
		
		return $list;
	}
	
	private function getDefaultHeadColumn ()
	{
		$itemList = new ItemList();
		$item = new Item ();
		$item->setName('');
		$itemList->add ($item);
		
		$weekday = date('l', strtotime($this->_theDate)); 
		
		$item2 = new Item ();
		$item2->setId ($this->_theDate);  
		$item2->setName ($weekday);
		$itemList->add ($item2);
		
		return $itemList;
	}
	
	private function getAppointmentItem ($startTime)
	{
		if ($this->_list == null || $this->_list->size() == 0)
			return null;
			
		$list = $this->_list;
		$count = $list->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $list->get($i);
			$start = $item->getProperty ('start_time');
			$end = $item->getProperty ('end_time');
			//Log::debug ('startTime:' .$startTime.', start:' .$start .', end:' .$end);
			$block = $item->getProperty ('time_block');	
			$n = DatetimeUtil::compare ($startTime, $start);
			if ($n == 0)
				return $item;
			else if ($n > 0)
			{
				$m = DatetimeUtil::compare($startTime, $end);
				if ($m < 0)
					return $item;			
			}
		}
		return null;
	}
	
	private function getResourceOnOffTime ($resourceId, $date)
	{
		$this->_onTimeInfo = ServiceTimeHelper::getServiceTimeByResource ($resourceId, $date);	
		$this->_offTimeInfo = ServiceTimeHelper::getOffTimeByResource ($resourceId, $date);
	}
	
	private function isUserResource()
	{
		$isRes = false;
		if ($this->_userId != 0 && $this->_resourceId != 0)
		{
			$isRes = ($this->_userId == $this->_resourceId);
			if (AccountTypes::isAdmin($this->_accountType) || AccountTypes::isBookKeeper($this->_accountType))
				$isRes = true;
		}
		
		return $isRes;	
	}
	
	private function isEditable ($startTime, $endTime)
	{
		if ($this->_resourceId == 0)
			return false;
			
		$editable = AppointmentHelper::isEditable ($this->_theDate, $startTime, $endTime, 
									$this->_resourceId, $this->_accountType, $this->_userId);
		if (!$editable)
			return false;
			
		//first get the service time see if out of service time
		$info = $this->_onTimeInfo;
		if ($info != null)
		{
			$start = $info->getStartTime();
			$end = $info->getEndTime();
			//Log::debug ('start:' .$start .', end:' .$end);
			if ($endTime <  $start || $startTime > $end)
				$editable = false;
		}
		//Log::debug ('startTime:' .$startTime .', editable:' .$editable);
		//now check off day
		if ($editable)
		{
			$info = $this->_offTimeInfo;			
			if ($info != null)
			{
				//Log::debug ('after getOffTime:' .$info->getId());
				$start = $info->getStartTime();
				$end = $info->getEndTime();
				//Log::debug ('start:' .$start .', end:' .$end .'    sTime:' .$startTime);
				if ($startTime < $end || $endTime > $start)
					$editable = false;			
			}
		}
			
		return $editable;		
		
		
	}
}
?>