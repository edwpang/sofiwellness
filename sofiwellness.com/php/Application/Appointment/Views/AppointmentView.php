<?php
/*
 * $Id: ReminderView.php,v 1.2 2009/05/21 19:47:25 gorsen Exp $
 * FILE:ReminderView.php
 * CREATE: Feb 20, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */

$highlightFunc = CalendarTable::getTagBkJsFunc();
  		
$clickFunc = CalendarTable::getCellClickFunc();

$this->headWriter = new HeadItemWriter();
$this->headWriter->addJsDesc($highlightFunc);
$this->headWriter->addJsDesc($clickFunc);
  		
//include header
include APP_COMMON_DIR . '/Views/header.php';
//set the current is the active in main menu bar

$base_url = '/appointment';

if (Utils::getUserId () != null && !AccountTypes::isCustomerAccount(Utils::getAccountType()))
{
include 'appointment_toolbar.php';	
}



?>

<table style="width:100%">

<tr><td>


<?php
	$calendar = new CalendarTable();
	$calendar->setEditUrl ('/appointment/edit?');
	$calendar->setYear($this->year);
	$calendar->setMonth($this->month);
	$calendar->setItemList ($this->sourceList);
	$calendar->setLockCallbackFunc ($this->lock_callback_func);
	$calendar->output();
?>
<br/>
</td>
</tr>
</table>


<?php
include APP_COMMON_DIR . '/Views/footer.php';
?>