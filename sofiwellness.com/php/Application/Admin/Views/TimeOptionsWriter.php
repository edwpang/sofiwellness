<?php
/*
 * $Id:$
 * FILE:TimeOptionsWriter.php
 * CREATE: Jun 21, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class TimeOptionsWriter extends AbstractUiComponent
{
	private $_timeList = null;
	private $_selectedStartTime = null;
	
	public function setTimeList ($list)
	{
		$this->_timeList = $list;
	}
	
	public function setSelectedTime ($start)
	{
		$this->_selectedTime = $start;	
		Log::debug ('startTime:' .$this->_selectedStartTime);
	}
	
	protected function doWrite (HtmlWriter $writer)
	{
		$count = $this->_timeList->size();
		for ($i = 0; $i < $count; $i++)
		{
			$timeItem = $this->_timeList->get($i);
			$writer->write('<option ');
			$writer->write(' value="'.$timeItem.'"');
			if ($this->_selectedTime != null && $timeItem == $this->_selectedTime)
			$writer->write(' selected');
			$writer->write( '>');
			$writer->write( $timeItem);
			$writer->write( '</option>');
		}
	}	
}
?>