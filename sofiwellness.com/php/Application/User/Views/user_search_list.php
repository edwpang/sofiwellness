<?php
/*
 * $Id:$
 * FILE:user_search_list.php
 * CREATE: Jun 25, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
$list = $this->user_list;
$url = '/user/edit?id=';
?>
<h1>Search Results</h1>
<div id="user_list" style="width:70%">
<?php
$generalList = new GeneralList ();	
$generalList->setItems ($list);
$generalList->output();
?>


</div>
