<?php
/*
 * $Id:$
 * FILE:service_edit_form.php
 * CREATE: Jun 22, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$info = $this->service_item;
?>
<form name="service-edit-form" action="/admin/serviceedit/save" method="post">
<input type=hidden name="id" value="<?php echo $info->getId();?>"/>
<input type=hidden name="language" value="<?php echo $info->getLanguage();?>"/>

<div id="appointment_enter_form" class ="gform" style="width:760px;border:1px solid gray">
	<div id="time-section">
		<h2><?php echo $info->getTitle();?></h2>
		<div class="row">
			<label class="col_r">Title:</label>
			<span class="col_l">
			<input type="text" name="title" value="<?php echo $info->getTitle();?>" size="65" tabindex="1"/>
			</span>
		</div>
		<div class="row">
			<label class="col_r">Description:</label>
			<span class="col_l">
			<textarea name="description" cols= "50" rows="12" id="description" name="description" tabindex="2"><? echo $info->getDescription(); ?>
			</textarea>
			</span>
		</div>
	</div>
	
	<div class="row" style="text-align:center; margin:20px 0;">
		<input class="button-b" type="submit" name="save" value="Save" tabindex="3" />
	</div>
	
</div>
</form>