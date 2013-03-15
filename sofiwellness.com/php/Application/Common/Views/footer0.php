<?php
/*
 * Created on May 1, 2007
 *
 */

//for layout
?>
<br/>
</div> <!-- #content -->
</div> <!-- #container -->
<div id="footer" style="<?php echo $this->footerStyle;?>">

        <p>
        Copyright &#169; 2009 <?php echo GlobalConstants::SITE_NAME;?>. All rights reserved. 
        &nbsp;&nbsp;
        <a href="/info/terms" class="nodec">Terms of Service</a>
        &nbsp;|&nbsp;
        <a href="/info/privacy" class="nodec">Privacy Statement</a>
       </p>
		
</div> <!-- end #footer -->

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