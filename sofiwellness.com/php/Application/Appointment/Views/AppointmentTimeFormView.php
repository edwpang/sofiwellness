<?php
/*
 * $Id:$
 * FILE:AppointmentTimeFormView.php
 * CREATE: May 25, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
//include header
include APP_COMMON_DIR . '/Views/header.php';
$info = $this->info;
if ($info == null)
	Log::debug ("## view info = null");
else
	Log::debug ('ddd' .$info->getFirstName());
?>


<div id='content_left' style="width:5%">


</div>

<div id='content_right' style="width:85%">

<form name="schedule-time-picker" action="/appointment/edit/savebook" method="post">
    <input type=hidden name="id" value="<? echo $this->info->getId();?>"/>
    <input type=hidden name="start_time" value="<? echo $this->startDateTime;?>"/>
    <input type=hidden name="res_id" value="<? echo $this->info->getResourceId();?>"/>
    <input type=hidden name="user_id" value="<? echo $this->info->getUserId();?>"/>
   

<fieldset class="frm">
<legend>
<span>Pick schedule time</span>
</legend>
<ol>
<li>
<label for="first_name" class="label">First Name:</label>
<input id="first_name" name="first_name"  type="text" value="<?php echo $this->info->getFirstName(); ?>" readonly/>
   Last Name:
<input id="last_name" name="last_name"  type="text" value="<?php echo $this->info->getLastName(); ?>" readonly/>
</li>
<li>
<label for="who" class="label">Resource:</label>
<input id="who" name="who"  type="text" value="<? echo $this->info->getResName(); ?>" readonly/>
</li>
<li>
<label for="start" class="label">Start At:</label>
<input id="start" name="start" type="text" value="<? echo $this->startTime; ?>" readonly/>
</li>
<li>
<label for="period">Time Duration:</label>
<div class="fields">
<input type="radio" name="period" value="30">30 minutes<br>
<input type="radio" name="period" value="45">45 minutes<br>
<input type="radio" name="period" value="60" checked>60 minutes<br>
<input type="radio" name="period" value="90">90 minutes<br>
<input type="radio" name="period" value="120">120 minutes<br>
</div>
</li>
<li/>
<li>
<label> </label>
<input type="submit" name="submit" value="Submit"/>
</li>
</ol>
</fieldset>
</form>

</div>

<?php
include APP_COMMON_DIR . '/Views/footer.php';
?>