<?php
/*
 * $Id:$
 * FILE:appointment_enter_form.php
 * CREATE: May 26, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$info = $this->form;
$uInfo = $this->form->getUserInfo();

?>
<form name="schedule-time-picker" action="/appointment/edit/save" method="post">
<input type=hidden name="appointment_id" value="<?php echo $info->getId();?>"/>
<input type=hidden name="user_id" value="<?php echo $uInfo->getId();?>"/>
<input type=hidden name="picked_date" value="<?php echo $info->getDay();?>"/>
<input type=hidden name="res_id" value="<?php echo $info->getResourceId();?>"/>

<div id="appointment_enter_form" class ="gform" style="width:820px;border:1px solid gray">
<?php
if ($info->getId () != 0)
{
	$hrefDel = '/appointment/edit/delete?id=' .$info->getId();
	echo '<div style="float:right;margin: 5px 50px 0 0;">';
	echo '<a class="btn-red" href="'.$hrefDel .'">Cancel Appointment</a>';
	echo '</div>';	
}
?>
	<div id="info-section">
		<h2>Info</h2>
		<div class="row">
			<label class="col_r"><em>*</em>First Name:</label>
			<span class="col_l"><input type="text" name="first_name" value="<?php echo $info->getFirstName();?>" class="req"/></span>
			
			<label class="col_r"><em>*</em>Last Name:</label>
			<span class="col_l"><input type="text" name="last_name"  value="<?php echo $info->getLastName();?>" class="req"/></span>
		</div>
		<div class="row">
			<label class="col_r">Address:</label>
			<span class="col_l"><input type="text" name="address" value="<?php echo $uInfo->getAddress();?>" size="65"/></span>
		</div>

		<div class="row">
			<label class="col_r">Email:</label>
			<span class="col_l"><input type="text" name="email" value="<?php echo $uInfo->getEmail();?>"/></span>
			<label class="col_r">Phone:</label>
			<span class="col_l"><input type="text" name="phone" value="<?php echo $uInfo->getPhone();?>"/></span>
		</div>
		<div class="row">
			<label class="col_r">Cell:</label>
			<span class="col_l"><input type="text" name="cell" value="<?php echo $uInfo->getCell();?>"/></span>
		</div>
	</div>
	<div id="app-section">
		<h2>Appointment</h2>
		
		<div class="row">
			<label class="col_r"><em>*</em>Resource:</label>
			<span class="col_l"><input type="text" name="who" value="<?php echo $info->getResourceName();?>" readonly /></span>
		</div>
		<div class="row">
			<label class="col_r">Date:</label>
			<span class="col_l"><input id="appointment_date" name="appointment_date" value="<?php echo $info->getDay();?>" class="req" size="28"/></span>
			<span class="col_r"><button id="cd-trigger" class="cdLink"></button></span>
			<span class="gray small"> format:yyyy-mm-dd</span>
		</div>
		<div class="row">
			<label class="col_r">From:</label>
			<span class="col_l"><input type="text" name="start_time" value="<?php echo $info->getTimeFrom();?>" readonly/></span>
			<label class="col_r">To:</label>
			<span class="col_l">
			<select name="end_time"  value="<?php echo $info->getTimeTo();?>">
				<?php 
					$count = $this->endtime_list->size();
					for ($i = 0; $i < $count; $i++)
					{
						$timeItem = $this->endtime_list->get($i);
						echo '<option ';
						echo ' value="'.$timeItem.'"';
						if ($this->select_endtime != null && $timeItem == $this->select_endtime)
							echo ' selected';
						echo '>';
						echo $timeItem;
						echo '</option>';
					}
				?>
			</select>
			</span>
		</div>
		
	</div>


	<div class="row" style="text-align:center; margin:20px 0;">
		<input class="button-b" type="submit" name="save" value="Submit" tabindex="6" />
	</div>
	
</div> <!-- END FORM DIV -->

</form>