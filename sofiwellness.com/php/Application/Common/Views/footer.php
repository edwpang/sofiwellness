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