<?php
/*
 * $Id:$
 * FILE:AppointmentSettingView.php
 * CREATE: May 30, 2010
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

include 'setting_toolbar.php';
?>


<div id="main_content">


<div id='content_left' style='width:10%;'>
<?php

if ($this->resource_list != null)
{
	$resPicker = new VerticalImgMenu();
	$resPicker->setItems ($this->resource_list);
	$resPicker->output();
}
?>
</div>

<div id='content_right' style="width:80%; margin-right:20px">
<?php
if ($this->right_panel != null)
	include $this->right_panel;
?>
</div>

</div> 

<?php
include APP_COMMON_DIR . '/Views/footer.php';
?>