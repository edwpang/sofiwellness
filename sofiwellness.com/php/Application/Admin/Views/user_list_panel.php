<?php
/*
 * $Id:$
 * FILE:user_list_panel.php
 * CREATE: May 16, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$this->headList = new ItemList ();
$itemName = new Item ();
$itemName->setName ('Name');
$this->headList->add ($itemName);
$itemAct = new Item ();
$itemAct->setName ('Action');
$this->headList->add ($itemAct);

if (null != $this->sourceList)
{
	//Log::debug ('productList:count=' .$this->sourceList->size());
	$table = new SsTable ();

	$table->setHeadItemList($this->headList);
	$table->setItemList ($this->sourceList);
	$table->setStyle ('width:80%;float:left');
	$table->setSortImage (GlobalConstants::getSysImage('arrow_up.gif'), GlobalConstants::getSysImage('arrow_down.gif'));
	$table->output();
}
?>