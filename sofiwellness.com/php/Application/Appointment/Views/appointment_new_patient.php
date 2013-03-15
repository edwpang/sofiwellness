<?php
/*
 * $Id:$
 * FILE:appointment_new_patient.php
 * CREATE: Jul 16, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
//include header
include APP_COMMON_DIR . '/Views/header.php';
//set the current is the active in main menu bar

$base_url = '/apointment';
?>

<div id="main_content">


<div id='content_left' style='width:10%'>

</div>

</div>

<div id='content_right' style="width:85%; margin-right:30px;">

<div style="margin:50px; 50px;">
<?php 
	$msg = 'If you are new patient, please call  following phone number to make an appointment:<br/><br/>';
	$msg .= '<span class="bold">' .GlobalConstants::CONTACT_PHONE .'</span>';
	$msg .= '<br/><br/>';
	$msg .= 'Thank you.<br/><br/>';
	
	$alert = new AlertBox ();
	$alert->setClass ('info');
	$alert->setMessage ($msg);
	$alert->output();
?>
</div>


</div>


</div>


<?php
include APP_COMMON_DIR . '/Views/footer.php';
?>