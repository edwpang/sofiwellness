<?php
/*
 * $Id:$
 * FILE:HomeView.php
 * CREATE: May 14, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$view = $this->view;

include APP_COMMON_DIR .'/Views/header.php';


$ref_service = '/' .GlobalConstants::TABID_SERVICE;
?>


<div id="home_content" style="width:95%;margin:20px 20px;">

<br>

<h1 class="welcome"> Welcome to Sophie Wellness Centre</h1><br>

<img src="images/sys/logo.jpg" align="left" padding="2px">

<blockquote>Go into partnership with nature; she does more than half the work and asks none of the fee. - <i>Martin H. Fischer</i></blockquote>
<p>
Sophie Wellness Centre is a <b>multi-disciplinary</b> Clinic offering a wide range of alternative and complementary therapies. Our <u>practitioners</u> will work together with you and get to the root of what gets in the way of good health. Our goal is to assist your body's natural healing ability, not only bring you back to your natural state of health, but also to prevent illness from occurring again. </p>
<iframe src="https://www.facebook.com/plugins/like.php?href=https://www.facebook.com/greenhill.rehab"
        scrolling="no" frameborder="0"
        style="border:none; width:450px; height:80px">
</iframe>
        
<table border="0" align="center" class="imgs">
<tr>
<td valign="top"><img src="/images/sys/entrance.jpg"></td>
<td valign="top"><img src="/images/sys/waiting.jpg"></td>
<td valign="top"><img src="/images/sys/reception.jpg"></td>
</tr>
</table>




</div>



<?php
include APP_COMMON_DIR .'/Views/footer.php';
?>