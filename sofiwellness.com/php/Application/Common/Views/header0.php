<?php
/*
 * Created on May 1, 2007
 *
 */

$accountType = Utils::getAccountType();
if ($accountType != null && AccountTypes::isAdmin($accountType))
      $this->view_mode = 'admin';

$this->title .= ' - a clinic offering a wide range of alternative and complementary therapies';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=7" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
	<meta name="robots" content="follow, all" />
	<meta NAME="description" content="Greenhill Rehab Clinic is a multi-disciplinary Clinic offering a wide range of alternative and complementary therapies, including massage therapy, acupuncture, chiropractor, skin care and so on.">
	<meta name="keywords" content="rehab clinic, massage therapy, acupuncture, chiropractor, skin care, Herabl Medicine, Far Infrared Sauna Therapy">	
    <title><?php echo $this->title; ?></title>
    <link rel="shortcut icon" href="favicon.ico" />
    <link href="/css/main_style.css" rel="stylesheet" type="text/css" />
    <script src="/js/jquery-1.4.1.js"  language="javascript"  type="text/javascript"></script>
    <script src="/js/app_source.js"  language="javascript"  type="text/javascript"></script>
<?php
if ($this->headWriter != null)
{
	$this->headWriter->output();	
}
?>
</head>

<?php
$bkColor = '#3399cc';//'#FFC641';
$this->headerStyle = "high: 100px;background-color:".$bkColor;
$this->footerStyle = "border-top: 1px solid " .$bkColor .";background:" .$bkColor; 
?>

<body BGCOLOR="<?php echo $bkColor;?>"> 
<script type="text/javascript" src="/js/dhmltooltip/wz_tooltip.js"></script>


<? 

$siteName = GlobalConstants::SITE_NAME;

?>
<div id="container" style="width:90%; margin:0 5%; background:#fff;">
<div id="content" style="width:100%;">

<div id="header" class="head_bk"  style="<?php echo $this->headerStyle;?>">
<table style="width:100%; margin:0;paddings:0">
<tr>
<td rowspan=3">
<a href="/home">
<?php 
$logoImg = GlobalConstants::getLogoImage();
if ($logoImg != null)
	echo '<img src="' .$logoImg .'" class="logo_l" alt="' .$siteName .'"/>';

?>
</a></td>
<td>&nbsp;</td>
</tr>
<tr>
</tr>
<tr>
<td>&nbsp;</td>
<td align="right" valign=bottom style="padding-right:30px">
<?php 
	if (Utils::getUserId() != null)
	{
?>
	<a href="/auth/user/logout" style="color:blue"><span class="bold-small">Logout</span></a>
<?php 
	}
	else
	{
?>
		<a href="/auth/user/login" style="color:blue"><span class="bold-small">Login</span></a>
		|<a href="/auth/signup" style="color:blue"><span class="bold-small">Signup</span></a>
<?php
	}
?>
</td>
</tr>
</table>

</div> 

<?php 
//show Main menubar
include 'main_menubar.php';  	
?>