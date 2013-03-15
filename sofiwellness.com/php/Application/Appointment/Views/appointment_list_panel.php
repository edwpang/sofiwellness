<?php
/*
 * $Id: reminder_list_panel.php,v 1.1 2009/02/26 19:56:23 gorsen Exp $
 * FILE:reminder_list_panel.php
 * CREATE: Feb 23, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
?>

<?php
//list the current page messages
$listTable = new ReminderListTableMaker ();


//$listTable->setOnClickFunc ('getReminderAndUpdate');
//$listTable->setAjaxUpdateDivId ('message_content');
$listTable->setList ($this->sourceList);
$listTable->setTitle ('My Reminder on ' .$this->curDate);
$listTable->setTotalRows ($this->pageSize);
$listTable->setStyle ('width:280px');
$listTable->setUrl ($this->url);
$html = $listTable->create();

echo $html;

Log::debug ('done table');
//if the total message > page size, show the page navigation bar
$total = $this->total;
$pageNumber = $this->pageNumber;
$pageSize = $this->pageSize;
Log::debug ('pageSize:' .$pageSize);
$totalPage = 0;
if ($pageSize > 0)
	$totalPage = intval( $total/ $pageSize) + 1;
$action = $this->action;

Log::debug('$totalPage='.$totalPage .',pageSize='.$pageSize .', pageNumber=' .$pageNumber);

if ($totalPage > 1)
{
	$start = $pageNumber - 1;
	if ($start == 0)
		$start = 1;
	$end = intval($total/$pageSize) + 1;
	Log::debug('start=' .$start .', end=' .$end);

	$pager = new PagingNav();
	$pager->setCurrent ($pageNumber);
	$pager->setTotal ($totalPage);
	$pager->setRange ($start, $end);
	$pager->setUrlBase ($this->url);

	$htmlPager = $pager->create();
	echo $htmlPager;
}

?>
