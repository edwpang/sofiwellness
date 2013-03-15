<?php
/*
 * $Id: feed_source_toolbar.php,v 1.3 2008/12/10 20:26:16 gorsen Exp $
 * FILE:feed_source_toolbar.php
 * CREATE: Nov 11, 2008
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
//menu items

$listItems = array();


$itemNew = new Item();
$itemNew->setName ('Add User');
$itemNew->setHref ('/admin/edit');
$itemNew->setClass ('newuser');
$itemNew->setTooltip ('Add User');
array_push ($listItems, $itemNew); 

$itemSet = new Item();
$itemSet->setName ('Setup Hours');
$itemSet->setHref ('/admin/servicetime');
$itemSet->setClass ('schedule');
$itemSet->setTooltip ('Setup service times');
array_push ($listItems, $itemSet); 

$itemSvc = new Item();
$itemSvc->setName ('Setup Service');
$itemSvc->setHref ('/admin/serviceedit');
$itemSvc->setClass ('setting');
$itemSvc->setTooltip ('Setup services');
array_push ($listItems, $itemSvc); 

$toolbar = new Toolbar();
$toolbar->setStateItems ($stateItems);
$toolbar->setItems ($listItems);
$userName = Utils::getUserName();
$toolbar->setUserName($userName);
$toolbar->output();
?>