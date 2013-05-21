<?php
/*
 * Created on May 1, 2007
 *
 */

//for layout
?>

</div> <!-- #content -->

<div id="footer" style="<?php echo $this->footerStyle;?>">
<div id="footer_content" class="foot_content">

        <p>
        Copyright &#169; 2013 <?php echo GlobalConstants::SITE_NAME;?>. All rights reserved. 
        &nbsp;&nbsp;
        <a href="/info/terms" class="nodec">Terms of Service</a>
        &nbsp;|&nbsp;
        <a href="/info/privacy" class="nodec">Privacy Statement</a>
       </p>
<!-- Google Code for website Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 990146348;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "yju3CKTSsVIQrN6R2AM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/990146348/?value=0&amp;label=yju3CKTSsVIQrN6R2AM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
       
</div> <!-- end #footer_content -->
</div> <!-- #footer -->

<?php 
if ($this->footWriter != null)
{
	Log::debug ('$this->footWriter != null');
	$this->footWriter->output();
	echo "\n";
}
?>



</body>
</html> 