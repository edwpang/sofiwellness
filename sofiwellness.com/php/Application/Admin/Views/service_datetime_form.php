<?php
/*
 * $Id:$
 * FILE:service_datetime_form.php
 * CREATE: Jun 1, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$info = $this->info;
$timeWriter = new TimeOptionsWriter();
$timeWriter->setTimeList ($this->time_list);
?>
<form name="service-datetime-form" action="/admin/servicetime/save" method="post">
<div id="appointment_enter_form" class ="gform" style="width:760px;border:1px solid gray">
	<div id="time-section">
		<h2>Normal Business Time</h2>
		<div class="row">
			<label class="col_r">Weekdays:</label>
			<span class="col_l_350">
			<label>From</label>
			<select name="start_time"  value="<?php echo $info->getStartTime();?>">
				<?php 
				   $timeWriter->setSelectedTime ($info->getStartTimeStr());
				   $timeWriter->output();
				?>
			</select>
			<label>to</label>
			<select name="end_time"  value="<?php echo $info->getEndTime();?>">
				<?php 
				   $timeWriter->setSelectedTime ($info->getEndTimeStr());
				   $timeWriter->output();
				?>
			</select>
			</span>
		</div>		
		
		<div class="row">
			<label class="col_r">Saturday:</label>
			<span class="col_l_350">
			<label>From</label>
			<select name="start_time_sat"  value="<?php echo $this->info_sat->getStartTime();?>">
				<?php 
				   $timeWriter->setSelectedTime ($this->info_sat->getStartTimeStr());
				   $timeWriter->output();
				?>
			</select>
			<label>to</label>
			<select name="end_time_sat"  value="<?php echo $this->info_sat->getEndTime();?>">
				<?php 
				   $timeWriter->setSelectedTime ($this->info_sat->getEndTimeStr());
				   $timeWriter->output();
				?>
			</select>
			</span>
		</div>		
		<div class="row">
			<label class="col_r">Sunday:</label>
			<span class="col_l_350">
			<label>From</label>
			<select name="start_time_sun"  value="<?php echo $this->info_sun->getStartTime();?>">
				<?php 
				   $timeWriter->setSelectedTime ($this->info_sun->getStartTimeStr());
				   $timeWriter->output();
				?>
			</select>
			<label>to</label>
			<select name="end_time_sun"  value="<?php echo $this->info_sun->getEndTime();?>">
				<?php 
				   $timeWriter->setSelectedTime ($this->info_sun->getEndTimeStr());
				   $timeWriter->output();
				?>
			</select>
			</span>
		</div>		
	</div>
	
	<div id="weekdays-section">
		<h2>Dates</h2>
		<p style="margin-left:30px;">Check if the business will close.</p>
		<div class="row">
		<label class="col_r">Weekdays:</label>
		<span class="col_l_500">
		<?php
			$dates = DatetimeUtil::getWeekDayNames();
			foreach ($dates as $key => $day)
			{
				$name = strtolower ($day);
				if ($name == 'saturday')
					break;
				$bCheck = false;
				if ($this->list_off_day != null)
				{
					$count = $this->list_off_day->size();
					for ($i = 0; $i < $count; $i++)
					{
						$item = $this->list_off_day->get($i);
						if ($name == $item->getTheDate())
						{
							$bCheck = true;
							break;	
						}
					}
				}
				$check = '';
				if ($bCheck)
					$check = 'checked';
				echo '<input type="checkbox" name="'.$name.'" value="'.$name .'" '.$check .'/>';
				echo '<label>'.$day .'</label>';
			}
		?>
		</span>
		</div>
		<div class="row">
		<label class="col_r">Weekens:</label>
		<span class="col_l_500">
		<?php
			$dates = DatetimeUtil::getWeekDayNames();
			foreach ($dates as $key => $day)
			{
				$bCheck = false;
				$name = strtolower ($day);
				if ($this->list_off_day != null)
				{
					$count = $this->list_off_day->size();
					for ($i = 0; $i < $count; $i++)
					{
						$item = $this->list_off_day->get($i);
						if ($name == $item->getTheDate())
						{
							$bCheck = true;
							break;	
						}
					}
				}
				if ($name == 'saturday' || $name == 'sunday')	
				{			
					$check ='';
					if ($bCheck)
						$check = 'checked';
					echo '<input type="checkbox" name="'.$name.'" value="'.$name .'" '.$check.'/>';
					echo '<label>' .$day .'</label>';
				}
			}
		?>
		</span>
		</div>
	</div>
	
	<div id="date-section">
		<h2>Holidays</h2>
		<p style="margin-left:30px;">Check if the business will close.</p>
		<?php
			$map = CanadaHolidayCalculator::getAllHolidays(date('Y'));
			$listDays = $map->getAll();
			foreach ($listDays as $name => $day)
			{
				$bCheck = false;
				if ($this->list_holiday != null)
				{
					$count = $this->list_holiday->size();
					for ($i = 0; $i < $count; $i++)
					{
						$item = $this->list_holiday->get($i);
						if ($name == $item->getTheDate())
						{
							$bCheck = true;
							break;	
						}
					}
				}
				$check = '';
				if ($bCheck)		
					$check = 'checked';
				
				$sId = strtolower ($name);
				$sId = str_replace (" ", "_", $sId);
				echo '<div class="row">';
				echo '<label class="col_r">' .$name.':</label>';
				echo '<span class="col_l_350">';
				echo '<input type="checkbox" name="'.$sId.'" value="'.$day .'" '.$check.'/>';
				echo '</span>';
				echo '</div>';
			}
		?>
	</div>

	<div class="row" style="text-align:center; margin:20px 0;">
		<input class="button-b" type="submit" name="save" value="Submit" tabindex="6" />
	</div>

</div>
</form>
