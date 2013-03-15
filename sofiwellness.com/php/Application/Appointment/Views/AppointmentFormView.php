<?php
/*
 * $Id:$
 * FILE:AppointmentFormView.php
 * CREATE: May 26, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */


$this->headWriter = new HeadItemWriter();
$this->headWriter->setCssFolder ('/js/jscalendar');
$this->headWriter->addCssInclude ('calendar-blue-m.css');
$this->headWriter->setJsFolder (JS_FOLDER .'/jscalendar');
$this->headWriter->addJsInclude('calendar.js');
$this->headWriter->addJsInclude('lang/calendar-en.js');
$this->headWriter->addJsInclude('calendar-setup.js');

//include header
include APP_COMMON_DIR . '/Views/header.php';
//set the current is the active in main menu bar

$base_url = '/apointment';
?>

<div id="main_content">


<div id='content_left' style='width:10%'>
<?php 

?>
</div>
</div>

<div id='content_right' style="width:85%; margin-right:30px;">

<div id="<?php echo GlobalConstants::SHOW_MESSAGE?>" style="width:70%">

<?php
//include 'AppointmentEntryForm.php';
include 'appointment_enter_form.php';
?>
<br/>



<script type="text/javascript">
  Calendar.setup(
    {
      inputField  : "appointment_date",         // ID of the input field
      ifFormat    : "%Y-%m-%d",    // the date format
      button      : "cd-trigger"       // ID of the button
    }
  );
</script>


</div>


</div>


<?php
include APP_COMMON_DIR . '/Views/footer.php';
?>