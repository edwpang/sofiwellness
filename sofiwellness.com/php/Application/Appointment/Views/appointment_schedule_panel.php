<?php
/*
 * $Id:$
 * FILE:appointment_schedule_panel.php
 * CREATE: May 25, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$showDate = DatetimeUtil::formatDatetimeStr ($this->the_date, DatetimeUtil::FULLNAME_D_PATTEN);
$prevDate = DatetimeUtil::addDays ($this->the_date, 1, '-');
$prevDate = DatetimeUtil::formatDatetimeStr ($prevDate, DatetimeUtil::DATE_PATTEN);
$nextDate = DatetimeUtil::addDays ($this->the_date, 1, '+');
$nextDate = DatetimeUtil::formatDatetimeStr ($nextDate, DatetimeUtil::DATE_PATTEN);
$hrefPrev = '?date='.urlencode($prevDate);
$hrefNext = '?date='.urlencode($nextDate);
$mvPrev = "Tip('".$prevDate ."' , BGCOLOR, 'lightyellow', SHADOW, true)";
$mvNext = "Tip('".$nextDate ."' , BGCOLOR, 'lightyellow', SHADOW, true)";
?>

<div id="schedule-time-list" style="float:left;width:60%;margin-bottom:40px;">

<a href="<?php echo $hrefPrev;?>" style="color:#2f5c80" onmouseover="<?php echo $mvPrev;?>">&#9668;</a>

Date: <?php echo $showDate;?>

<a href="<?php echo $hrefNext;?>" style="color:#2f5c80" onmouseover="<?php echo $mvNext;?>">&#9658;</a>

<?php
$scheduleWriter = new ScheduleWriter ();
$scheduleWriter->setDate($this->the_date);
$scheduleWriter->setAccountType (Utils::getAccountType());
$scheduleWriter->setItems ($this->source_list);
if ($this->start_time != null)
	$scheduleWriter->setHours($this->start_time, $this->end_time);

$scheduleWriter->setResourceId($this->resource_id);
$scheduleWriter->setUserId ($this->user_id);
$scheduleWriter->output ();

?>

</div>



<div id="calendar-pickup" style="float:right; width:35%; margin-right:20px;">

<?php
$calWriter = new MiniCalendarTable();
$calWriter->setDate ($this->the_date);
$calWriter->setBaseUrl ('/appointment/edit?');
$calWriter->setOffDate ($this->off_date_list);
$calWriter->output();
?>
<br class="clear"/>

<!-- div id= "cd-div" style="width:220px;margin-top:30px;"> </div -->

</div>

</div>

<p class="clear"/>

<script type="text/javascript">
/*
function dateChanged(calendar) {
    // Beware that this function is called even if the end-user only
    // changed the month/year.  In order to determine if a date was
    // clicked you can use the dateClicked property of the calendar:
    if (calendar.dateClicked) {
      var y = calendar.date.getFullYear();
      var m = calendar.date.getMonth();     // integer, 0..11
      m = m + 1;  //for us, month start at 1 - 12
      var d = calendar.date.getDate();      // integer, 1..31
      window.location = "/appointment/edit?y=" + y + "&m=" + m + "&d=" + d;
    }
  };


  Calendar.setup(
    {
      ifFormat    : "%Y-%m-%d",    // the date format
      flat        : "cd-div",       // ID of the div to hold flat calendar
	  flatCallback : dateChanged   //callback function
	}
  );
 */
</script>

