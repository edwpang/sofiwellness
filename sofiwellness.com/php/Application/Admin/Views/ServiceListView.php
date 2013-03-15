<?php
/*
 * $Id:$
 * FILE:service_list_panel.php
 * CREATE: Jun 22, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
include APP_COMMON_DIR .'/Views/header.php';

include 'user_edit_toolbar.php';

$listInfo = $this->source_list;
$total_service = $listInfo->size();
$mid_point = ceil($total_service / 2);
?>


<div id="main_content" style="width:90%;margin:10px 20px;">
<div style="width:60%; margin-left:50px;">
<?php
$alertBox = new AlertBox ();
$alertBox->setClass ('info');
$alertBox->setMessage ('To modify the service item, click at the title of the service.');
$alertBox->output();
?>

</div>
<h1>Services and Fees </h1>

<div id='content_left' style='width:45%;padding-right:10px;'>

<?php
    $url = '/admin/serviceedit/form?id=';
	for ($i = 0; $i < $mid_point; $i++)
	{
		$info = $listInfo->get($i);	
		$id = $info->getId();
		$href = $url .$id;
		echo '<div class="svc-list">';
		echo '<h3><a href="'.$href .'" class="norm">' .$info->getTitle() .'</a></h3>';
		echo '<div id="'.$info->getImageRef() .'" class="svc-img"></div></td>';
		echo '<div class="text" id="' .$info->getId() .'">';
		echo $info->getDescription();
		echo '</div>';
		echo '</div>';
	}
?>

</div>


<div id='content_right' style="width:45%;padding-left:10px;">

<?php
	for ($i = $mid_point; $i < $total_service; $i++)
	{
		$info = $listInfo->get($i);	
		$id = $info->getId ();
		$href = $url .$id;
		echo '<div class="svc-list">';
		echo '<h3><a href="'.$href .'" class="norm">' .$info->getTitle() .'</a></h3>';
		echo '<div id="'.$info->getImageRef() .'" class="svc-img"></div></td>';
		echo '<div class="text" id="' .$info->getId() .'">';
		echo $info->getDescription();
		echo '</div>';
		echo '</div>';
	}
?>

</div>

<div class="clear">
<p>Gift Certificates for all occasions can be purchased for any services listed. 
It makes the perfect gift for anyone who enjoys the benefits of relaxation and wellness.</p>
<p>We accept payment: cash, cheque, Visa, MasterCard or Debit</p>
<br/>
<img src="/images/sys/payments.png"/>
</div>



</div>


<?php
include APP_COMMON_DIR .'/Views/footer.php';
?>