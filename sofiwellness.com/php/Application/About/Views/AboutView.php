<?php
/*
 * $Id:$
 * FILE:AboutView.php
 * CREATE: Jun 21, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
include APP_COMMON_DIR .'/Views/header.php';
?>
<div id="resource-list" style="width:70%; margin:20px 50px;">

<h1 class="welcome">Specialists in <?php echo GlobalConstants::SITE_NAME; ?> </h1>

<p>
We are delighted to be able to offer a wide range of therapies by fully trained, 
insured and experienced specialists. 
</p>

<?php
	if ($this->resource_list != null && $this->resource_list->size() > 0)
	{
		$list = $this->resource_list;
		$count = $list->size();
		for ($i = 0; $i < $count; $i++)
		{
			$item = $list->get($i);	
			
			echo '<div class="svc-list" style="border-top:none;">';
			echo '<div class="name">';
			$image = $item->getImage ();
			if ($image != null)
			{
				echo '<img src="'.$image .'" alt="'.$item->getName() .'" style="border:1px solid gray;">';
				echo '<br/>';
			}
			echo $item->getName();
			echo '</div>';
			
			//now description
			if ($item->getDesc() != null)
			{
				echo '<div class="text" style="padding-top:5px;">';
				echo $item->getDesc();
				echo '</div>';
			}
			
			echo '</div>';
		}	
		
	}
?>

</div>

<div style="width:100%;margin-top:40px;text-align:center;">
<img src="css/images/facility.png" alt="Sophie Wellness Clinic"/>
</div>
<?php
include APP_COMMON_DIR .'/Views/footer.php';
?>