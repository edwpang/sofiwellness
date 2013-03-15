<?php
/*
 * $Id:$
 * FILE:LoginView.php
 * CREATE: May 16, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$this->footWriter = new HeadItemWriter();
$this->footWriter->setJsFolder(JS_FOLDER);
$this->footWriter->addJsInclude('md5.js');
$this->footWriter->addJsDesc($validFunc);



 
include APP_COMMON_DIR . '/Views/header.php';

if ($this->$left_panel_style == null)
	$this->$left_panel_style = "width:10%;";
if ($this->$right_panel_style == null)
	$this->$right_panel_style = "width:85%;";
?>

<div id="panel_container">

<div id="content_left" style="<?php echo $this->left_panel_style;?>">

<?php
if ($this->left_panel != null)
	include $this->left_panel;
else
{
	if ($this->left_panel_writer != null)
		$this->left_panel_writer->output();
}
?>


</div> <!-- left_panel -->


<div id="content_right" style="<?php echo $this->right_panel_style;?>">

<?php
if ($this->right_panel != null)
	include $this->right_panel;
else
{
	if ($this->right_panel_writer != null)
		$this->right_panel_writer->output();
}
?>
</div> <!-- right_panel -->


</div> <!-- panel_container -->

<?php
include APP_COMMON_DIR . '/Views/footer.php';
?>