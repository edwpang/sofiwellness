<?php
/*
 * $Id:$
 * FILE:appointment_setting_form.php
 * CREATE: May 30, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$form = $this->service_time_form;
?>

<h1><?php echo $this->current_user; ?></h1>
<form name="schedule-time-setting-form" action="/appointment/setting/save" method="post">
<input type=hidden name="user_id" value="<?php echo $form->getUserId();?>"/>

<div id="schedule_time_setting" class ="gform" style="width:820px;border:1px solid gray">
	<div id="info-section">
		<h2>I am available on</h2>
		<?php 
			$timeWriter = new ServiceTimeWriter ();
			$timeWriter->setForm ($form);			
			$timeWriter->setListTime($this->time_list);
			$timeWriter->setSaturdayListTime($this->time_list_sat);
			$timeWriter->setSundayListTime($this->time_list_sun);
			$timeWriter->setWeekOffDayNames($this->weekday_off_names);
			$timeWriter->output();
		?>
	</div>


	<div class="row" style="text-align:center; margin:20px 0;">
		<input class="button-b" type="submit" name="save" value="Submit" tabindex="6" />
	</div>
	
</div> <!-- END FORM DIV -->

</form>