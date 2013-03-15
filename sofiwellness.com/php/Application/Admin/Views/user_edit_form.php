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
$userInfo = $this->userInfo;
if ($this->error_message != null)
{
	$alertBox = new AlertBox();
	$alertBox->setId (GlobalConstants::SHOW_MESSAGE);
	$alertBox->setMessage ($this->error_message);
	$alertBox->output();
}
?>

<form name="user_edit_form" enctype='multipart/form-data' action="<?php echo $this->form_url ?>" method="post">
    <input type=hidden name="id" value="<? echo $userInfo->getId();?>"/>

<fieldset class="frm">
<legend>
<span>User Form</span>
</legend>
<ol>
<li>
<label for="first_name" class="label"><em>*</em>First Name:</label>
<input id="first_name" name="first_name"  type="text" value="<? echo $userInfo->getFirstName(); ?>" <? echo $isReadOnly  ?>/>
</li>
<li>
<label for="last_name"><em>*</em>Last Name:</label>
<input id="last_name" name="last_name" type="text" value="<? echo $userInfo->getLastName(); ?>" <? echo $isReadOnly ?>/>
</li>
<li>
<label for="title">Title:</label>
<input id="title" name="title" type="text" value="<? echo $userInfo->getTitle(); ?>" <? echo $isReadOnly ?>/>
</li>
<li>
<label for="email">Email:</label>
<input id="email" name="email" type="text" value="<? echo $userInfo->getEmail(); ?>" <? echo $isReadOnly ?>/>
</li>
<li>
<label for="home_phone"><em>*</em>Phone:</label>
<input id="home_phone" name="phone" type="text" value="<? echo $userInfo->getPhone(); ?>" <? echo $isReadOnly ?>/>
</li>
<li>
<label for="cell">Cell:</label>
<input id="cell" name="cell" type="text" value="<? echo $userInfo->getCell(); ?>" <? echo $isReadOnly ?>/>
</li>
<li>
<label for="address">Address:</label>
<textarea name="address" cols= "37" rows="4" id="address" name="address" type="text">
<? echo $userInfo->getAddress(); ?>
</textarea>
</li>
<li>
<label for="type">Type:</label>
<select name="account_type">
<?php
	$types = $this->accountTypeList;
	$curTypeName = $this->accountType;
	$nType = $types->size();
	for ($i= 0; $i < $nType; $i++)
	{
		$definedType = $types->get($i);
		$typeId = $definedType;
		if ($definedType == $curTypeName)
			echo '<option value="' .$typeId .'" selected>' .$definedType .'</option>';
		else
			echo '<option value="' .$typeId .'">' .$definedType .'</option>';
	}
?>
</select>
</li>
<li>
<label for="type">Role:</label>
<select name="role_name">
<?php
	$roles = $this->role_name_list;
	$curName = $this->current_role;
	for ($i= 0; $i < $nType; $i++)
	{
		$role = $roles->get($i);
		if ($role == $curName)
			echo '<option value="' .$role .'" selected>' .$role .'</option>';
		else
			echo '<option value="' .$role .'">' .$role .'</option>';
	}
?>
</select>
</li>
<li>
<label for="upload">Upload Picture:</label>
<input id="upload" name="upload" class="text" type="file" style="width:400px"/>
</li>
<li/>
<?php 
	if ($this->mode != 'admin_edit')
		echo '<li><span style="color:blue;">Enter the ID for the account. The password will be the same as ID.</span></li>';
?>

<li>
<label for="user_name"><em>*</em>User ID:</label>
<input id="user_name" name="user_name" type="text" value="<? echo $userInfo->getUserName(); ?>" <? echo $isReadOnly ?>/>
</li>
<?php
	if ($this->mode == 'admin_edit')
	{
?>
<li>
<label for="re_password">Reset Password:</label>
<input id="re_password" name="re_password" type="checkbox"/> 
<span class="blue small-font">If checked, the password will reset to be same as user ID.</span>
</li>
<?php
	}
?>
<li>
<label for="description">Description:</label>
<textarea name="description" cols= "37" rows="4" id="description" name="description" type="text">
<? echo $userInfo->getDescription(); ?>
</textarea>
</li>
<li/>
<li>
<label> </label>
<input type="submit" class="btn-input" name="submit" value="Submit"/>
</li>

</ol>
</fieldset>


</form>