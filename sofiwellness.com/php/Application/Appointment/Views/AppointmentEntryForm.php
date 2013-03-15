<?php
/*
 * $Id: ReminderEntryForm.php,v 1.2 2009/05/21 19:47:25 gorsen Exp $
 * FILE:ReminderEntryForm.php
 * CREATE: Feb 22, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$formName = $this->editFormName;
if ($formName == null)
	$formName = 'appointment_edit_form';
	
$timeList = AppointmentHelper::getDefTimeList();
$dueTime = $this->dueTime;


$recurrence = 'once';
?>
<form name="<?php echo $formName ?>" action="<?php echo $this->editFormUrl ?>" method="post">
    <input type=hidden name="appointment_id" value="<? echo $this->appointmentInfo->getId();?>"/>
    <input type=hidden name="due_date" value="<? echo $this->dueDate;?>"/>

<fieldset class="frm">
<legend>
<span>Reminder</span>
</legend>
<ol>
<li>
<label for="subject" class="label">Subject<em>*</em>:</label>
<input id="subject" name="subject" style="width:50%;" type="text" value="<? echo $this->appointmentInfo->getSubject(); ?>"/>
</li>
<li>
<label for="time">Time:</label>
<input id="date" name="date" type="text" value="<?php echo $this->dueDate ?>"/>
<button id="cd-trigger" class="cdLink"></button>
<select name="time">
<?php
	$num = $timeList->size();
	for ($i = 0; $i < $num; $i++)
	{
		$t = $timeList->get($i);
		
		if ($t == $dueTime)
			echo '<option value="' .$t .'" selected>' .$t .'</option>';
		else
			echo '<option value="' .$t .'">' .$t .'</option>';
	}
?>
</select>
</li>
<li>
<label for="recurrence">Recurrence:</label>
<?php
	$recVals = array ('once','daily', 'weekly', 'biweekly', 'monthly');
	$i = 0;
	foreach ($recVals as $val)
	{
		$sel = null;
		if ($recurrence != null && $val == $recurrence)	
			$sel = 'checked';
		
		echo '<input type="radio" name="recurrence" value="' .$val .'"';
		if ($i > 0)
			echo 'style="margin-left:1em"';
		if ($sel != null)
			echo ' ' .$sel;
		echo  '>' .ucfirst($val); 
		$i++;
	}
?>
</li>
<li>
<label for="detail">Details:</label>
<textarea id="detail" name="detail"  rows="6"  type="text" wrap="on" style="width:50%;">
<? echo $this->appointmentInfo->getDetail(); ?>
</textarea>
</li>
</ol>
</fieldset>

</form>

<script type="text/javascript">
  Calendar.setup(
    {
      inputField  : "date",         // ID of the input field
      ifFormat    : "%Y-%m-%d",    // the date format
      button      : "cd-trigger"       // ID of the button
    }
  );
</script>
