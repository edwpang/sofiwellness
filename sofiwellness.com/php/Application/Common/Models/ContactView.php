<?php
/*
 * $Id:$
 * FILE:ContactView.php
 * CREATE: Jun 7, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
 
$gKey = 'ABQIAAAAdGprCKaoXUjdvBLnJfA1bRREA5sMwjvFfeqK3Ec7oksfNkkpWhQOQOrM-v2VFNqFNyHVgdJZ-w1wEg';
$googleJs = 'http://maps.google.com/maps?file=api&amp;v=2&amp;&amp';

$addrText = 'Sophie Wellness Centre';
$gmJs = '$(function() {
  if (GBrowserIsCompatible()) { 
      $map = document.getElementById("myMap"); 
      $m = new GMap2(map); 
      m.setCenter(new GLatLng(43.857368,-79.386821), 15); // pass in latitude, longitude, and zoom level.
      m.openInfoWindow(m.getCenter(), document.createTextNode("'.$addrText.'")); // displays the text
      m.setMapType(G_NORMAL_MAP); // sets the default mode. G_NORMAL_MAP, G_HYBRID_MAP

      $control = new GMapTypeControl(); // switch map modes
      m.addControl($control);
      m.addControl(new GLargeMapControl()); // creates the zoom feature
   }
  });';


$this->headWriter = new HeadItemWriter();
//$this->headWriter->setJsFolder(JS_FOLDER);
$this->headWriter->addJsInclude($googleJs);
$this->headWriter->addJsDesc($gmJs); 
//include header
include APP_COMMON_DIR . '/Views/header.php';
//set the current is the active in main menu bar



?>

<table style="background:#ffffff;border-collapse:collapse;">
<tr><td>
<div id="info" style="width:100%;">
<h3>Office Hour:</h3>
<p>
Monday to Friday: 10am to 7pm<br/>
Saturday: 9am to 4pm<br/>
Sunday: Closed<br/>
</p>
<br/>
<h3>Address:</h3>
<p>
Suite 309<br>
9140 Leslie Street<br/>
Richmond Hill<br/>
ON, L4B 0A9<br/>
</p>
<br/>
<p>Tel: <span class="bold"><?php echo GlobalConstants::CONTACT_PHONE;?></span></p>
<p>Fax: <span class="bold"><?php echo GlobalConstants::CONTACT_FAX;?></span></p>
<br/>
<img src="images/sys/office.png" style="float:left;"/>
</div>

</td>
<td style="width:65%;padding-right:10px">

<div id="myMap" style="width: 100%;height:450px;border:1px solid #666; margin-top:50px;"></div>

</td>
</tr>

</table>


<?php
include APP_COMMON_DIR . '/Views/footer.php';
?>