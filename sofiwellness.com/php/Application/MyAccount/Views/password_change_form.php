<?php
/*
 * $Id:$
 * FILE:password_change_form.php
 * CREATE: May 30, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$validFunc = "function validateForm (form, id)
{
	var len = form.password.value.length;
	var t = hex_hmac_md5(form.challenge.value, form.password.value);
	form.password.value= t.substr(0,len);	
	form.pwd.value= t;
	var clen = form.confirm.value.length;
	var ct = hex_hmac_md5(form.challenge.value, form.confirm.value);
	form.confirm.value= ct.substr(0,clen);	
	form.cpwd.value= ct;
			
	return true;
}";


$this->footWriter = new HeadItemWriter();
$this->footWriter->setJsFolder(JS_FOLDER);
$this->footWriter->addJsInclude('md5.js');
$this->footWriter->addJsDesc($validFunc); 


if ($this->error_message != null)
{
	$alertBox = new AlertBox();
	$alertBox->setId (GlobalConstants::SHOW_MESSAGE);
	$alertBox->setMessage ($this->error_message);
	$alertBox->output();
}
?>
<form name="password-form" action="/myaccount/edit/savepwd" onsubmit="return validateForm(this, 'err-message');" method="post">
<input type="hidden" name="id" value="<?php echo $info->getId();?>"/>
<input type="hidden" name="challenge" id="challenge" value="<?php echo GlobalConstants::PWD_SALT;?>"/>
<input type="hidden" name="pwd" value=""/>
<input type="hidden" name="cpwd" value=""/>

<div id="account_form" class ="gform" style="width:420px;border:1px solid gray">

	<div id="acnt-section">
		<h2>Change Password</h2>
		<div class="row">
			<label class="col_r"><em>*</em>New Password:</label>
			<span class="col_l"><input type="password" name="password" value=""/></span>
		</div>
		<div class="row">
			<label class="col_r"><em>*</em>Confirm Password:</label>
			<span class="col_l"><input type="password" name="confirm" value="" /></span>
		</div>
	</div>


	<div class="row" style="text-align:center; margin:20px 0;">
		<input class="button-b" type="submit" name="save" value="Save" tabindex="6" />
	</div>
	
</div> <!-- END FORM DIV -->

</form>

