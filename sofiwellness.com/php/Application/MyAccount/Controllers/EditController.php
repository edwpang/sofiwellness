<?php
/*
 * $Id:$
 * FILE:EditController.php
 * CREATE: May 28, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'MyAccountInclude.php';

class MyAccount_EditController extends BaseController
{
	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(MYACCOUNT_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_TITLE .':MyAccount';
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_MY_ACCOUNT);
	}	
	
    public function saveAction ()
    {
     	Log::debug ('EditController::saveAction');
     	$form = Utils::getSimpleFormData();
     	$userInfo = $this->formToUserInfo($form);
 		$err = $this->validate ($userInfo);
 		if ($err != null)
 		{
 			parent::setErrorMessage ($err);
 			parent::setForm ($userInfo);
 		}
 		else
 		{
 			if ($userInfo->getPhone() != null)
 			{
 				$phone = ValidateUtil::toPhoneFormat ($userInfo->getPhone());
 				$userInfo->setPhone ($phone);
 			}
 			
 			if ($userInfo->getCell() != null)
 			{
 				$phone = ValidateUtil::toPhoneFormat ($userInfo->getCell());
 				$userInfo->setPhone ($cell);
 			}
 			
 			//do save
 			Log::debug ('displayName:' .$userInfo->getDisplayName());
 			if ($userInfo->getId() != 0)
 			{
 				$oInfo = UserManager::getUserInfoById ($userInfo->getId());
 				$userInfo->setPassword ($oInfo->getPassword());
 				$userInfo->setCreateTime($oInfo->setCreateTime());
 				UserManager::updateUserInfo ($userInfo);
 			}
 			else
 			{
 				UserManager::saveUserInfo ($userInfo);	
 			}
 			//upload image handled by upload
 			
 		}
 		$this->_redirect ('/myaccount');	   	
    }
    
    public function changeAction ()
    {
     	Log::debug ('EditController::changeAction');  
     	$this->view->error_message = parent::getErrorMessage(); 	
     	$userId = Utils::getUserId();
     	$userInfo = new UserInfo();
     	$userInfo->setId ($userId);
     	$this->view->user_info = $userInfo;
     	parent::setRightPanel('password_change_form.php');
    	echo $this->view->render('MyAccountView.php');
     	
    }
    
    public function savepwdAction ()
    {
     	Log::debug ('EditController::savepwdAction');   
     	$form = Utils::getSimpleFormData();
     	$password = $form->get('password');  //in case js turned off
     	$confirm = $form->get ('confirm');
     	$pwd = $form->get('pwd');
     	if ($pwd != null)
     		$password = $pwd;  
     	$cpwd = $form->get ('cpwd');
     	if ($cpwd != null)
     		$confirm = $cpwd;
     		
     	$toUrl = '/myaccount';
     	//validate
     	if ($password == null || $password != $confirm)
     	{
     		parent::setErrorMessage ('The password and confirm not match.');
     		$toUrl = '/myaccount/edit/change';
     	}
     	else
     	{	
     		$userId = Utils::getUserId();
     		UserManager::updatePassword ($userId, $password);	
     	}
 		$this->_redirect ($toUrl);	   	
    }
    
    public function uploadAction()
    {
     	Log::debug ('EditController::uploadAction');  
     	$this->view->error_message = parent::getErrorMessage(); 	
     	$params = ServerParams::getQueryParameters();
     	$id = $params['id'];
     	$this->view->id = $id;
     	parent::setRightPanel('upload_form.php');
    	echo $this->view->render('MyAccountView.php');
    	
    }
    
    public function savepicAction ()
    {
    	Log::debug ('savepicAction');
    	$form = Utils::getSimpleFormData ();
    	$id = $form->get('id');
    	$userId = Utils::getUserId();
    	$userName = Utils::getUserName();
    	$imageInfo = new ImageInfo();
		$uploadFile = $this->handlePicUpload ($userId, $userName, $imageInfo);
    	if ($uploadFile != null)
    	{
    			
    	}
    	
    	$this->_redirect ('/myaccount');	  
    }
    
    ///////////////////////////////////////////////////
    // private
    private function formToUserInfo($form)
    {
    	$info = new UserInfo();
    	$info->setId ($form->get('id'));
    	$info->setUserName ($form->get('user_name'));
    	$password = $form->get('pwd');
    	if ($password == null || $password == '')
    		$password = $form->get('password');
    	$info->setPassword ($password);
    	$info->setFirstName ($form->get('first_name'));
    	$info->setLastName ($form->get('last_name'));
    	$info->setEmail ($form->get('email'));
    	$info->setPhone ($form->get('phone'));
    	$info->setCell ($form->get('cell'));
    	$info->setAddress ($form->get('address'));
    	$info->setTitle ($form->get('title'));
    	$info->setDisplayName ($form->get('display_name'));
    	$info->setDescription ($form->get('description'));
    	$info->setAccountType ($form->get('type'));
    	return $info;	
    }
    
    private function validate ($info)
    {
    	if ($info->getUserName() == null)
    		return 'User ID cannot be empty.';
    		
    	$phone = $info->getPhone();
    	if ($phone == null)
    		$phone = $info->getCell();
    	if ($phone == null && $info->getEmail() == null)
    		return 'You must enter phone or email';
    		
    	if ($phone != null && !ValidateUtil::validatePhoneNumber($phone))
    		return 'Invalid phone number';
    		
    	return null;	
    }
    
	private function handlePicUpload ($userId, $userName, $imageInfo)
	{
		Log::debug ('handlePicUpload');
		$handler = new ImageUploadHandler();
    	$handler->setUseType('avatar');
    	$handler->setNamePrefix($userName);
    	$handler->setImageLimitSize(GlobalConstants::AVATAR_IMG_WIDTH, GlobalConstants::AVATAR_IMG_HEIGHT);
    	$handler->setImageInfo ($imageInfo);
    	$handler->setUploadDir(GlobalConstants::getImageAvatarDir(), true);
    	$handler->setValidFileExt (array('png', 'jpg', 'gif'));
    	$uploadFile = $handler->process();
    	Log::debug ('uploadFile:' .$uploadFile);
    	if ($uploadFile != null)
    	{
    		//now save to database
    		$imgInfo = $handler->getImageInfo ();
    		$imgInfo->setUserId ($userId);
    		$imgInfo->setUserName ($userName);
     		$imgInfo->setTypeName ('avatar');
    		$imgInfo->setDescription ('avatar');
    		$db = new ImageInfoDbAccessor ();
    		$oInfo = $db->getImageInfoByUserId ($userId);
    		if ($oInfo != null)
    		{
    			$imgInfo->setId ($oInfo->getId());
    			$db->updateImageInfo ($imgInfo, false);
    		}
    		else
    			$db->saveImageInfo($imgInfo, false);
    	}
  		return $uploadFile;
	}
}
?>