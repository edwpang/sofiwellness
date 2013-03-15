<?php
/*
 * $Id:$
 * FILE:MyAccountView.php
 * CREATE: May 28, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */


 
//include header
include APP_COMMON_DIR . '/Views/header.php';

$info = $this->user_info;
$uploadPicUrl = '/myaccount/upload';
$myPic = '/images/sys/picture.gif';
$password = null;
if ($info != null)
{
	if ($info->getImageInfo () != null)
	{
		$myPic = $info->getImageInfo()->getFile();	
	}
	$password = $info->getPassword();
	$uploadPicUrl = '/myaccount/upload?id='.$info->getId();
}
?>


<div id="main_content">


<div id='content_left' style='width:10%;'>
<?php


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