<?php
/*
 * $Id:$
 * FILE:user_edit_form.php
 * CREATE: May 16, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
 
$validFunc = "function validateLoginForm (form, id)
{
	var len = form.password.value.length;
	var t = hex_hmac_md5(form.challenge.value, form.password.value);
	form.password.value= t.substr(0,len);	
	form.pwd.value= t;
	var clen = form.password.value.length;
	var ct = hex_hmac_md5(form.challenge.value, form.confirm.value);
	form.confirm.value= ct.substr(0,clen);	
	form.cpwd.value= t;
	return true;
}";


$this->footWriter = new HeadItemWriter();
$this->footWriter->setJsFolder(JS_FOLDER);
$this->footWriter->addJsInclude('md5.js');
$this->footWriter->addJsDesc($validFunc); 
 
 
 
 
$info = $this->user_info;
if ($this->error_message != null)
{
	$alertBox = new AlertBox();
	$alertBox->setId (GlobalConstants::SHOW_MESSAGE);
	$alertBox->setMessage ($this->error_message);
	$alertBox->output();
}
?>



<form name="customer_signup_form" onsubmit="return validateLoginForm(this, 'err-message');"  action="<?php echo $this->form_url ?>" method="post">
    <input type="hidden" name="challenge" id="challenge" value="<?php echo GlobalConstants::PWD_SALT;?>"/>
    <input type="hidden" name="pwd" id="pwd" value=""/>
    <input type="hidden" name="cpwd" id="c pwd" value=""/>

<div id="customer_signup" class ="gform" style="width:820px;border:1px solid gray">
	<div id="info-section">
		<h2>Please fill up the form:</h2>
		<div class="row">
			<label class="col_r"><em>*</em>First Name:</label>
			<span class="col_l"><input type="text" name="first_name" value="<?php echo $info->getFirstName();?>" class="req"/></span>
			
			<label class="col_r"><em>*</em>Last Name:</label>
			<span class="col_l"><input type="text" name="last_name"  value="<?php echo $info->getLastName();?>" class="req"/></span>
		</div>
		<div class="row">
			<label class="col_r">Address:</label>
			<span class="col_l"><input type="text" name="address" value="<?php echo $info->getAddress();?>" size="62"/></span>
		</div>

		<div class="row">
			<label class="col_r">Email:</label>
			<span class="col_l"><input type="text" name="email" value="<?php echo $info->getEmail();?>"/></span>
			<label class="col_r"><em>*</em>Phone:</label>
			<span class="col_l"><input type="text" name="phone" value="<?php echo $info->getPhone();?>" class="req"/></span>
		</div>
		<div class="row">
			<label class="col_r"><em>*</em>User ID:</label>
			<span class="col_l"><input type="text" name="user_name" value="<?php echo $info->getUserName();?>" class="req"/></span>
		</div>
		<div class="row">
			<label class="col_r"><em>*</em>Password:</label>
			<span class="col_l"><input type="password" name="password" value="" class="req"/></span>
		</div>
		<div class="row">
			<label class="col_r"><em>*</em>Retype Password:</label>
			<span class="col_l"><input type="password" name="confirm" value="" class="req"/></span>
		</div>
		
	</div>


	<div class="row" style="text-align:center; margin:20px 0;">
		<input class="button-b" type="submit" name="save" value="Submit" tabindex="6" />
	</div>
	
</div> <!-- END FORM DIV -->

</form>