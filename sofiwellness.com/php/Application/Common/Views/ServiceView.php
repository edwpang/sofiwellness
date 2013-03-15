<?php
/*
 * $Id:$
 * FILE:ServiceView.php
 * CREATE: Jun 18, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */


include APP_COMMON_DIR .'/Views/header.php';

$listInfo = $this->source_list;
$total_service = $listInfo->size();
$mid_point = ceil($total_service / 2);

?>


<div id="panel_container" style="width:96%;margin:10px 20px;">
<h1 class="welcome">Services and Fees </h1>
<p class="italic">
All rates are including applicable taxes.
</p>

<div id="service_list_block" style="width:98%">
<ul class="svc">
<?php
	
	for ($i = 0; $i < $total_service; $i++)
	{
		$info = $listInfo->get($i);		
		$image = GlobalConstants::getImageSysDir() . '/' .$info->getImageRef();
		
		echo '<li>';
		echo '<h3>' .$info->getTitle() .'</h3>';
		echo $info->getDescription();
		echo '</li>';

	}
?>
</ul>
</div>

<!-- /div -->


<!-- div id='content_right' style="width:45%;padding-left:10px;" -->

<?php
/*
	for ($i = $mid_point; $i < $total_service; $i++)
	{
		$info = $listInfo->get($i);	
		echo '<div class="svc-list">';
		echo '<h3>' .$info->getTitle() .'</h3>';
		echo '<div id="'.$info->getImageRef() .'" class="svc-img"></div></td>';
		echo '<div class="text" id="' .$info->getId() .'">';
		echo $info->getDescription();
		echo '</div>';
		echo '</div>';
	}
*/
?>

<!-- /div -->

<div class="clear" style="width:80%; margin-left:50px;">
<hr style="width:80%"/>
<br/>
<p>Gift Certificates for all occasions can be purchased for any services listed. 
It makes the perfect gift for anyone who enjoys the benefits of relaxation and wellness.</p>
<p>We accept payment: cash, cheque, Visa, MasterCard or Debit.</p>
<br/>
<img src="/images/sys/payments.png"/>
</div>



</div>


<?php
include APP_COMMON_DIR .'/Views/footer.php';
?>