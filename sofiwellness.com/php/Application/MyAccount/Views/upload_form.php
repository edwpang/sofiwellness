<?php
/*
 * $Id:$
 * FILE:upload_form.php
 * CREATE: May 30, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
?>

<form name="upload-form"  enctype='multipart/form-data' action="/myaccount/edit/savepic" method="post">
<input type="hidden" name="id" value="<?php echo $this->id;?>"/>

<div id="upload_form" class ="gform" style="width:620px;border:1px solid gray">

	<div id="acnt-section">
		<h2>Upload your picture</h2>
		<div class="row">
			<label class="col_r"><em>*</em>File:</label>
			<span class="col_l">
			<input id="upload" name="upload" class="text" type="file" style="width:400px"/>
			</span>
		</div>
	</div>


	<div class="row" style="text-align:center; margin:20px 0;">
		<input class="button-b" type="submit" name="savepic" value="Submit" tabindex="6" />
	</div>
	
</div> <!-- END FORM DIV -->

</form>
