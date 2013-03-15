<?php
/*
 * $Id: reminder_edit_toolbar.php,v 1.2 2009/05/21 19:47:25 gorsen Exp $
 * FILE:reminder_edit_toolbar.php
 * CREATE: Feb 22, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$listItems = array();


$itemNew = new Item ();
$itemNew->setName ('New');
$itemNew->setHref ('/reminder/edit?act=new&y='.$this->year.'&m='.$this->month.'&d='.$this->date);
$itemNew->setClass ('new');
$itemNew->setProperty('not_form', true);
$itemNew->setTooltip ('add a new reminder');
array_push ($listItems, $itemNew); 


if ($this->reminderInfo != null && $this->reminderInfo->getId() > 0)
{
	$theUrl = '/reminder/edit/delete?id='.$this->reminderInfo->getId();
	$theUrl .= '&y=' .$this->year .'&m=' .$this->month .'&d='.$this->date;
	$itemDel = new Item ();
	$itemDel->setName ('Delete');
	$itemDel->setHref ($theUrl);
	$itemDel->setClass ('delete');
	$itemDel->setProperty(ItemTypes::NOT_FORM, true);
	$itemDel->setTooltip ('delete the reminder');
	array_push ($listItems, $itemDel); 	
}


if ($this->notAllowed == null || $this->notAllowed != true)
{
	$itemSave = new Item ();
	$itemSave->setName ('Save');
	$itemSave->setHref ($this->editFormUrl);
	$itemSave->setClass ('save');
	$itemSave->setTooltip ('Save');
	array_push ($listItems, $itemSave);
}

$itemCancel = new Item ();
$itemCancel->setName ('Cancel');
$itemCancel->setHref ('/reminder?y='.$this->year.'&m='.$this->month);
$itemCancel->setClass ('cancel');
$itemCancel->setProperty('not_form', true);
$itemCancel->setTooltip ('return to reminder view');
array_push ($listItems, $itemCancel); 


$toolbar = new Toolbar();
$toolbar->setStateItems ($stateItems);
$toolbar->setItems ($listItems);
$userName = Utils::getUserName();
$toolbar->setUserName($userName);
$toolbar->setFormName($this->editFormName);
$toolbar->output();
?>
