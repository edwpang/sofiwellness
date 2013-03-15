<?php
/*
 * $Id: ReminderEditView.php,v 1.1 2009/02/26 19:56:23 gorsen Exp $
 * FILE:ReminderEditView.php
 * CREATE: Feb 20, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */


$jsText = '$(document).ready(function(){
	$("#schedule td").hover(function(){
   		$(this).addClass("highlight");
   		if (!($(this).hasClass("lock")))
   			$(this).css(\'cursor\',\'pointer\');
  	},
  	function(){
   		$(this).removeClass("highlight");
   		$(this).css(\'cursor\',\'auto\');
  	})
});

$(document).ready(function() {

    $("#schedule td").click(function() {
        if (!($(this).hasClass("lock")))
        $(this).addClass("lock");
        var href = $(this).find("a").attr("href");
        if(href) {
             document.location.href  =  href;
        }
    });
});';





//for calendar

$this->headWriter = new HeadItemWriter();
$this->headWriter->setCssFolder ('/js/jscalendar');
$this->headWriter->addCssInclude ('calendar-blue-m.css');
$this->headWriter->setJsFolder (JS_FOLDER .'/jscalendar');
$this->headWriter->addJsInclude('calendar.js');
$this->headWriter->addJsInclude('lang/calendar-en.js');
$this->headWriter->addJsInclude('calendar-setup.js');
$this->headWriter->addJsDesc ($jsText);

//include header
include APP_COMMON_DIR . '/Views/header.php';
//set the current is the active in main menu bar

$base_url = '/apointment';


//include 'appointment_edit_toolbar.php';
?>

<div id="main_content">


<div id='content_left' style='width:160px'>
<h3 style="margin:30px 0 0 20px;">Make appointment with:</h3>
<?php
$defImage = '/images/sys/def_resource.png';
$resPicker = new VerticalImgMenu();
$resPicker->setDefImage ($defImage);
$resPicker->setItems ($this->resource_list);
$resPicker->output();

?>
</div>

<div id='content_right' style="width:80%;">

<div id="<?php echo GlobalConstants::SHOW_MESSAGE?>" style="width:70%">
<?php 
if ($this->message != null)
	echo '<span class="alert">';
echo $this->message;
if ($this->message != null)
	echo '</span>';

?>
</div>


<?php
//include 'AppointmentEntryForm.php';
include 'appointment_schedule_panel.php';
?>
<br/>
<br/>


</div>


</div>


<?php
include APP_COMMON_DIR . '/Views/footer.php';
?>