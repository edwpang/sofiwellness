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
$info = $this->user_info;
?>

<form name="user-search-form" action="user/index/search" method="post">
<input type=hidden name="id" value="<?php echo $info->getId();?>"/>


<div id="account_form" class ="gform" style="width:820px;border:1px solid gray">

	<div id="info-section">
		<h2>Search User:</h2>
		<div class= "row">
		<p style="margin-left: 10px; font-size:0.8em;">Enter the search criteria, either names or phone.</P>
		</div>
		<div class="row">
			<label class="col_r">First Name:</label>
			<span class="col_l"><input type="text" name="first_name" value="<?php echo $info->getFirstName();?>" /></span>
			
			<label class="col_r">Last Name:</label>
			<span class="col_l"><input type="text" name="last_name"  value="<?php echo $info->getLastName();?>" /></span>
		</div>
		<div class="row">
			<label class="col_r">Email:</label>
			<span class="col_l"><input type="text" name="email" value="<?php echo $info->getEmail();?>"/></span>
			<label class="col_r">Phone:</label>
			<span class="col_l"><input type="text" name="phone" value="<?php echo $info->getPhone();?>"/></span>
		</div>
	</div>


	<div class="row" style="text-align:center; margin:20px 0;">
		<input class="button-b" type="submit" name="search" value="Search" tabindex="6" />
	</div>
	
</div> <!-- END FORM DIV -->

</form>
