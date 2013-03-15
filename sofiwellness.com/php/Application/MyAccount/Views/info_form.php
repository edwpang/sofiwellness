<?php
/*
 * $Id:$
 * FILE:info_form_panel.php
 * CREATE: May 28, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */


if ($this->error_message != null)
{
	$alertBox = new AlertBox();
	$alertBox->setId (GlobalConstants::SHOW_MESSAGE);
	$alertBox->setMessage ($this->error_message);
	$alertBox->output();
}
?>

<form name="user-info-form" action="/myaccount/edit/save" onsubmit="return validateLoginForm(this, 'err-message');" method="post">
<input type=hidden name="id" value="<?php echo $info->getId();?>"/>
<input type=hidden name="type" value="<?php echo $info->getAccountType();?>"/>

<div id="account_form" class ="gform" style="width:820px;border:1px solid gray">

	<div id="info-section">
		<h2>Contact Info</h2>
		<div class="row">
			<label class="col_r">DisplayName</label>
			<span class="col_l"><input type="text" name="display_name" value="<?php echo $info->getDisplayName();?>" size="62"/></span>
		</div>
		<div class="row">
			<label class="col_r"><em>*</em>First Name:</label>
			<span class="col_l"><input type="text" name="first_name" value="<?php echo $info->getFirstName();?>" class="req"/></span>
			
			<label class="col_r"><em>*</em>Last Name:</label>
			<span class="col_l"><input type="text" name="last_name"  value="<?php echo $info->getLastName();?>" class="req"/></span>
		</div>
		<div class="row">
			<label class="col_r">Title:</label>
			<span class="col_l"><input type="text" name="title" value="<?php echo $info->getTitle();?>" /></span>
		</div>
		<div class="row">
			<label class="col_r">Address:</label>
			<span class="col_l"><input type="text" name="address" value="<?php echo $info->getAddress();?>" size="62"/></span>
		</div>

		<div class="row">
			<label class="col_r">Email:</label>
			<span class="col_l"><input type="text" name="email" value="<?php echo $info->getEmail();?>"/></span>
			<label class="col_r">Phone:</label>
			<span class="col_l"><input type="text" name="phone" value="<?php echo $info->getPhone();?>"/></span>
		</div>
		<div class="row">
			<label class="col_r">Cell:</label>
			<span class="col_l"><input type="text" name="cell" value="<?php echo $info->getCell();?>"/></span>
		</div>
	</div>
	<div id="acnt-section">
		<h2>Account Info</h2>
		<div class="row">
			<label class="col_r"><em>*</em>User ID:</label>
			<span class="col_l"><input type="text" name="user_name" value="<?php echo $info->getUserName();?>" readonly /></span>
		</div>
		<div class="row">
			<label class="col_r">Password:</label>
			<span class="col_l_350">
			<input type="password" name="none" value="******" readonly/>
			<a href="/myaccount/edit/change" class="norm marl2 small-font">Change Password</a>
			</span>
		</div>
		<div class="row">
			<label class="col_r">My Picture:</label>
			<span class="col_l">
			<a href="/myaccount/edit/upload?id=<?php echo $info->getId();?>">
				<img src="<?php echo $myPic;?>" alt="Upload your picture" style="border:1px solid #666"/>
			</a>
			</span>
		</div>		
	</div>

	<div class="row">
		<label class="col_r">Description:</label>
		<span class="col_l">
		<textarea name="description" cols= "45" rows="4" id="description" name="description" type="text"><? echo $info->getDescription(); ?>
		</textarea>
		</span>
	</div>

	<div class="row" style="text-align:center; margin:20px 0;">
		<input class="button-b" type="submit" name="save" value="Save" tabindex="6" />
	</div>
	
</div> <!-- END FORM DIV -->

</form>
