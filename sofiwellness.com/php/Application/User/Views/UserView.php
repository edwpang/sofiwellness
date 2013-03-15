<?php
/*
 * $Id:$
 * FILE:UserView.php
 * CREATE: Jun 25, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
 
  		
//include header
include APP_COMMON_DIR . '/Views/header.php';

?>

<div id="main_content">


<div id='content_left' style='width:1%;'>
<?php


?>
</div>

<div id='content_right' style="width:80%; margin-right:60px;">

<?php
if ($this->right_panel != null)
	include $this->right_panel;
else
{
	if ($this->right_panel_writer != null)
		$this->right_panel_writer->output();
}
?>
<br/>
</div>

</div>


<?php
include APP_COMMON_DIR . '/Views/footer.php';
?>