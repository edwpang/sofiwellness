<?php
/*
 * $Id:$
 * FILE:IndexController.php
 * CREATE: May 28, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'MyAccountInclude.php';

class MyAccount_IndexController extends BaseController
{
	

	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(MYACCOUNT_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_TITLE .':MyAccount';
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_MY_ACCOUNT);
	}

    public function indexAction()
    {
    	Log::debug ('MyAccountIndex::indexAction');
    	$params = ServerParams::getQueryParameters();
		
		$error = parent::getErrorMessage();
		$this->view->error_message = $error;
		$userId = Utils::getUserId ();
		$userInfo = UserManager::getUserInfoById ($userId);
		if ($userInfo != null)
		{
			Log::debug ('##displayName:' .$userInfo->getDisplayName());
			$db = new ImageInfoDbAccessor();
			$imageInfoList = $db->getImageByUserIdType ($userId, 'avatar');
			if ($imageInfoList != null && $imageInfoList->size() > 0)
			{
				$imageInfo = $imageInfoList->get(0);
				$imgFile = GlobalConstants:: getAvatarImage($imageInfo->getFile());
				$imageInfo->setFile ($imgFile);
				$userInfo->setImageInfo ($imageInfo);
			}
		}
		
		$this->view->user_info = $userInfo;
    	parent::setRightPanel('info_form.php');
    	echo $this->view->render('MyAccountView.php');
    }
    

}
?>