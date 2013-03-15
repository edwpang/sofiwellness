<?php
/*
 * $Id: reminder_toolbar.php,v 1.1 2009/02/26 19:56:23 gorsen Exp $
 * FILE:reminder_toolbar.php
 * CREATE: Feb 24, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */


$listItems = array();

$item = new Item ();
$item->setName('Availability');
$item->setClass ('schedule');
$item->setHref ('/appointment/setting');
array_push ($listItems, $item);

$toolbar = new Toolbar();
$toolbar->setStateItems ($stateItems);
$toolbar->setItems ($listItems);
$userName = Utils::getUserName();
$toolbar->setUserName($userName);
$toolbar->setFormName($this->editFormName);
$toolbar->output();
?>