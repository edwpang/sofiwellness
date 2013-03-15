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

?>


<div id="home-content" style="width:90%;margin:20px 20px;">

<div class="quote_box" style="width:60%; margin:10px 20%;">
<img src="images/sys/flower.jpg" align="left"/>
<p>Go into partnership with nature; she does more than half the work and asks none of the fee.<p>
<div style="float:right">Martin H. Fischer</div>
</div>

<h1>Welcome to Greenhill Rehab Clinic</h1>
<div id="intro" style="font-size:1.1em; margin:0px 40px;">
<p>
<span class="bold">Greenhill Rehab Clinic</span> is 
a multi-disciplinary Clinic offering a wide range of alternative and complementary therapies. 
Our practitioners will work together with you and get to the root of what gets in the way of good health. 
Our goal is to assist your body's natural healing ability, 
not only bring you back to your natural state of health, but also to prevent illness from occurring again.
</p>
</div>

<div id="service-intro" style="width:100%;margin:20px 50px; border:0px solid red;">

<div id="img-list" style="width:100%;float:left;">
<a href="/service" class="norm"><div id="img-svc-massage" class="svc-imgitem"></div></a>
<a href="/service" class="norm"><div id="img-svc-acup" class="svc-imgitem"></div></a>
<a href="/service" class="norm"><div id="img-svc-herbal" class="svc-imgitem"></div></a>
<a href="/service" class="norm"><div id="img-svc-chiropractor" class="svc-imgitem"></div></a>
<a href="/service" class="norm"><div id="img-svc-skincare" class="svc-imgitem"></div></a>
<a href="/service" class="norm"><div id="img-svc-sauna" class="svc-imgitem"></div></a>
</div>
<div id="svc-desc" style="width:100%;float:left;">
<div class="svc-desc"><a href="/service">Massage Therapy</a></div>
<div class="svc-desc"><a href="/service">Acupuncture</a></div>
<div class="svc-desc"><a href="/service">Herabl Medicine</a></div>
<div class="svc-desc"><a href="/service">Chiropractor</a></div>
<div class="svc-desc"><a href="/service">Natural Skin Care</a></div>
<div class="svc-desc"><a href="/service">Far Infrared Sauna Therapy</a></div>
</div>

</div> 

</div>



<?php
include APP_COMMON_DIR .'/Views/footer.php';
?>