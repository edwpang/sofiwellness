<?php
/*
 * $Id:$
 * FILE:setting_toolbar.php
 * CREATE: May 31, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$listItems = array();

$item = new Item();
$item->setName('General');
$item->setClass ('schedule');
$item->setHref ('/appointment/setting');
array_push ($listItems, $item);

$href = '/appointment/setting/day';
if ($this->current_user_id != null)
	$href .= '?id=' .$this->current_user_id;

$item2 = new Item ();
$item2->setName ('Special Day');
$item2->setClass ('newpage');
$item2->setHref ($href);
array_push ($listItems, $item2);

$toolbar = new Toolbar();
$toolbar->setStateItems ($stateItems);
$toolbar->setItems ($listItems);
$userName = Utils::getUserName();
$toolbar->setUserName($userName);
$toolbar->setFormName($this->editFormName);
$toolbar->output();
?>