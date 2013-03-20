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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="follow, all" />
<meta name="description"
	content="Sophie Wellness Centre is a multi-disciplinary Clinic offering a wide range of alternative and complementary therapies, including massage therapy, acupuncture, chiropractor, skin care and so on.">
	<meta name="keywords"
		content="WELLNESS, REHAB, CLINIC, RICHMOND HILL, MARKHAM, THORNHILL, BACK PAIN, PHYSICAL THERAPY, REHABILITATION, MASSAGE, THERAPY, ACUPUNCTURE, CHIROPRACTOR, CHIROPRACTIC, PAIN FREE, SENIORS, WALK IN CLINIC, JOINT, PAIN RELIEF, RECOVERY, WELLBEING, SKIN CARE, HERABL, NECK PAIN, LEG PAIN, ARM PAIN, CHINESE MEDICINE, INFRARED SAUNA">
		<title><?php echo $this->title; ?></title>

		<link rel="shortcut icon" href="/images/sys/favicon.gif"
			type="image/x-icon" />
		<link href="/css/main_style.css" rel="stylesheet" type="text/css" />
		<script src="/js/jquery-1.4.1.js" language="javascript"
			type="text/javascript"></script>
		<script src="/js/app_source.js" language="javascript"
			type="text/javascript"></script>

		<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-39059364-1']);
	  _gaq.push(['_setDomainName', 'sofiwellness.com']);
	  _gaq.push(['_setAllowLinker', true]);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
    </script>
		<?php
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
		{
			echo '<link href="/css/mobile.css" rel="stylesheet" type="text/css" />';
			echo '<meta name="viewport" initial-scale=1.0">';
			// echo 'Mobile Detected!';
		}
		?>
		<?php
		if ($this->headWriter != null)
		{
			$this->headWriter->output();
		}
		?>

</head>

<?php
$bkColor = '#9fd0b9';//'#3399cc';//'#FFC641';
$this->headerStyle = "background-color:".$bkColor;
$this->footerStyle = "border-top: 1px solid " .$bkColor .";background:" .$bkColor;
$this->footerStyle .=";margin:0%;";
$content_attrib = ""; //width:1280px;margin:auto;";
$siteName = GlobalConstants::SITE_NAME;
$body_bkColor = '#ffffff';
?>

<body BGCOLOR="<?php echo $body_bkColor;?>";>
	<script type="text/javascript" src="/js/dhmltooltip/wz_tooltip.js"></script>


	<div id="header" style="<?php echo $this->headerStyle;?>;<?php echo $content_attrib?>height:112px;">
		<div id="header_content" class ="header_content">

			<div id="hd_image" style="width: 100%; align: center;">

				<table style="width: 100%;" valign="top" border="0">
					<tbody>
						<tr>
							<td><h0>Sophie Wellness Centre</h0></td>
							<td valign=top>
								<span class="bold-small">Call 905-886-8399</span>
							</td>
							<td align="right" valign=top style="padding-right:30px">
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
					</tbody>
				</table>

			</div>


			<?php 
			//show Main menubar
			include 'main_menubar.php';
			?>
		</div>
	</div>
	<!-- header -->

	<div id="container" class ="content"">