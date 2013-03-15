<?php
/*
 * $Id:$
 * FILE:appointment_setting_date_panel.php
 * CREATE: May 31, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */


$form = $this->form; //the serviceFormItem
$times = $this->timeList;
$n = $times->size() - 1;
$defEndTime = $times->get($n);
$check = !$form->getOn();
?>

<h1><?php echo $this->current_user;?></h1>

<form name="service-date-picker-form" action="/appointment/setting/savedate" method="post">
<input type=hidden name="user_id" value="<?php echo $form->getId();?>"/>

<div id="service-date-picker" class ="gform" style="width:820px;border:1px solid gray">
	<div id="info-section">
		<h2>Service Day Pick</h2>
		<div class="row">
			<label class="col_r"><em>*</em>Date:</label>
			<span class="col_l"><input id="service_date" name="service_date" value="<?php echo $form->getDate();?>" class="req" size="28"/></span>
			<span class="col_r"><button id="cd-trigger" class="cdLink"></button></span>
			<span class="gray small-font"> format:yyyy-mm-dd</span>
		</div>
		<div class="row">
			<label class="col_r">Day Off:</label>
			<span class="col_l">
			<input id="date_off" name="date_off" type="checkbox" <?php echo $check;?>/>
			</span>
		</div>
		<div class="row">
			<label class="col_r">Start Time:</label>
			<span class="col_l">
				<select name="start_time">
				<?php 
					$st = $form->getStartTime();
					$count = $times->size();
					for ($i= 0; $i < $count; $i++)
					{
						$tItem = $times->get($i);
						if ($tItem == $st)
							echo ('<option value="' .$tItem .'" selected>' .$tItem .'</option>');
						else
							echo ('<option value="' .$tItem .'">' .$tItem .'</option>');
					}	
				?>
				</select>
			</span>		
		</div>
		<div class="row">
			<label class="col_r">End Time:</label>
			<span class="col_l">
				<select name="end_time">
				<?php 
					$st = $form->getEndTime();
					if ($st == null)
						$st = $defEndTime;
					$count = $times->size();
					for ($i= 0; $i < $count; $i++)
					{
						$tItem = $times->get($i);
						if ($tItem == $st)
							echo ('<option value="' .$tItem .'" selected>' .$tItem .'</option>');
						else
							echo ('<option value="' .$tItem .'">' .$tItem .'</option>');
					}	
				?>
				</select>
			</span>		
		</div>
		<div class="row">
			<label class="col_r">Note:</label>
			<span class="col_l">
			<input id="note" name="note" type="text" value="<?php echo $form->getNote();?>" size="65"/>
			</span>
		</div>
	</div>

	<div class="row" style="text-align:center; margin:20px 0;">
		<input class="button-b" type="submit" name="save" value="Submit" tabindex="6" />
	</div>


</div>
</form>





<script type="text/javascript">
  Calendar.setup(
    {
      inputField  : "service_date",         // ID of the input field
      ifFormat    : "%Y-%m-%d",    // the date format
      button      : "cd-trigger"       // ID of the button
    }
  );
</script>