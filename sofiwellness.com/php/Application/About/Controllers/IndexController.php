<?php
/*
 * $Id:$
 * FILE:IndexController.php
 * CREATE: Jun 21, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AboutInclude.php';

class Aboutus_IndexController extends BaseController
{

	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(ABOUT_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_TITLE .':About Us';
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_ABOUT_US);
	}

    public function indexAction()
    {
    	Log::debug ('###Aboutus::indexAction');
    	
    	$itemList = $this->getResourceItemList ();
    	$this->view->resource_list = $itemList;
     	
    	echo $this->view->render('AboutView.php');
    }
    
    
    ////////////////////////////////////////////////////////
    //private
    
    private function getResourceItemList ()
    {
    	$list =	UserManager::getUserInfoList (AccountTypes::PROFESSIONAL);
    	if ($list == null || $list->size() == 0)
    		return null;
    		
    	$imgDb = new ImageInfoDbAccessor();
    	$itemList = new ItemList ();
    	$count = $list->size();
    	for ($i = 0; $i < $count; $i++)
    	{
    		$info = $list->get($i);
    		Log::debug ('desc:' .$info->getDescription());
    		$item = $this->userInfoToItem ($info);
    		//get image
    		$imgList = $imgDb->getImageByUserIdType ($info->getId(), 'avatar');
    		if ($imgList != null && $imgList->size() > 0)
    		{
    			$imgInfo = $imgList->get(0);
    			$imgFile = GlobalConstants::getAvatarImage ($imgInfo->getFile());
    			$item->setImage ($imgFile);
    			if ($imgInfo->getImageWidth() != null)
    				$item->setProperty('image_width', 	$imgInfo->getImageWidth());
    			if ($imgInfo->getImageHeight() != null)
    				$item->setProperty('image_height', $imgInfo->getImageHeight());
    		}
    		$itemList->add ($item);
    	}
    	
    	
    	return $itemList;
    }
    
    private function userInfoToItem ($userInfo)
    {
    	$item = new Item();
    	$item->setId ($userInfo->getId());
    	$name = $userInfo->getDisplayName ();
    	if ($name == null)
    		$name = $userInfo->getFirstName() .' ' .$userInfo->getLastName ();
    	if ($name == null)
    		$name = $userInfo->getUserName();
    	$item->setName ($name);	
    	$des = $userInfo->getDescription();
    	if ($des != null)
    		$des = Utils::replaceLineBreakWithHtmlTags ($des);
    	$item->setDesc ($des);
    	
    	return $item;
    }
}
?>