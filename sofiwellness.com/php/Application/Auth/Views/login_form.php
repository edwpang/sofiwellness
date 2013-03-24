<?php
/*
 * $Id: login_form.php,v 1.2 2009/10/08 19:50:04 gorsen Exp $
 * FILE:login_form.php
 * CREATE: Sep 2, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
//add java script to foot section
$validFunc = "function validateLoginForm (form, id)
{
	var len = form.password.value.length;
	var t = hex_hmac_md5(form.challenge.value, form.password.value);
	form.password.value= t.substr(0,len);	
	form.pwd.value= t;
	return true;
}";


$this->footWriter = new HeadItemWriter();
$this->footWriter->setJsFolder(JS_FOLDER);
$this->footWriter->addJsInclude('md5.js');
$this->footWriter->addJsDesc($validFunc);

$tipMaker = new DynamicTooltip();
$tipLogin = $tipMaker->createTip ('login');

if ($this->error_message != null)
{
	$alertBox = new AlertBox();
	$alertBox->setId (GlobalConstants::SHOW_MESSAGE);
	$alertBox->setMessage ($this->error_message);
	$alertBox->output();
}
?>

<form name="login_form" action="/auth/user/authenicate"
		onsubmit="return validateLoginForm(this, 'err-message');" method="post">
    <input type="hidden" name="challenge" id="challenge" value="<?php echo GlobalConstants::PWD_SALT;?>"/>
    <input type="hidden" name="pwd" id="pwd" value=""/>
    <input type="hidden" name="on_success" value="<?php echo $this->on_success;?>"/>
   	<input type="hidden" name="on_failure" value="<?php echo $this->on_failure;?>"/>
    
<div id="customer_signin" class ="gform" style="margin-top:50px;width:420px;border:1px solid gray">
	<div id="info-section">
		<h2>Sign in:</h2>
		<div class="row">
			<table>
				<tr>
					<td><label style="width: 25%;" class="col_r"><em>*</em>User ID:</label></td>
					<td><span class="col_l"><input type="text" name="username" value="" class="req" tabindex="1"/></span></td>
				</tr>
				<tr>
					<td><label class="col_r"><em>*</em>Password:</label></td>
					<td><span class="col_l"><input type="password" name="password" value="" class="req" tabindex="2"/></span></td>
				</tr>
			</table>
		</div>
		<div class="row" style="text-align:center; margin:20px 0;">
			<input class="button-b" type="submit" name="save" value="Submit" tabindex="3" />
		</div>
	</div>
	<div style="margin:20px 30px; color:#666;">Don&acute;t have an account? 
		<a href="/auth/signup" class="norm">Sign up</a> for a <span class="red">free</span> account.</div>
</div>

</form>


