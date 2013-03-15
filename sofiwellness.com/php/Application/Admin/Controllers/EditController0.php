<?php
/*
 * $Id:$
 * FILE:EditController.php
 * CREATE: May 17, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AdminInclude.php';
 
class Admin_EditController extends BaseController
{
	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(ADMIN_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_NAME;
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_ADMIN);
         $this->view->view_mode = 'admin';
	}	

    public function indexAction()
    {
    	Log::debug ('editcontroller::indexAction');
    	
    	if (!UserManager::isAdmin ())
    	{
    		//to login
    		parent::setLastUrl ('/admin');
    		$this->_redirect('/auth/user/login');	
    		return;
    	}
    			//now show view
    	$this->view->error_message = parent::getErrorMessage(true);	
	   	$userInfo = parent::getForm(true);
	   	$userId = null;
    	if ($userInfo == null)
    	{
    		$params = ServerParams::getQueryParameters();
    		$userId = $params['id'];
    		if ($userId != null)
    		$userInfo = UserManager::getUserInfoById ($userId);
    	}
    		
    	$accountType = 'Professional';
    	if ($userInfo == null)
    		$userInfo = new UserInfo();
    	else
    	{
    		$type = $userInfo->getAccountType();
    		$accountType = AccountTypes::getAccountTypeNameByCode ($type); 
    	}
    	$this->view->userInfo = $userInfo;
    	$this->view->accountTypeList = AccountTypes::getAccountTypeNames();		
    	$this->view->accountType = $accountType;
    	$this->view->role_name_list = RoleNames::getRoleNameList (true);
    	$this->view->current_role = $userInfo->getRoleName();
    	$this->view->form_url = "/admin/edit/save";
    	if ($userId != null)
    		$this->view->mode = 'admin_edit';
		parent::setRightPanel ('user_edit_form.php');
		echo $this->view->render('AdminView.php');
    }

	public function saveAction()
	{
		Log::debug ('editcontroller::save');
		$forwardTo = '/admin/edit';
		$form = Utils::getSimpleFormData ();
		$userInfo = $this->formToUserInfo($form);
		$err = $this->validateUserForm ($form);
		if ($err != null)
		{
			Log::error ($err);
			parent::setErrorMessage ($err);		
		}
		else
		{
			//save user info
			$resetPassword = false;
			if ($form->get('re_password') != null)
				$resetPassword = true;
			$this->saveUser ($userInfo, $resetPassword);
			//now dealwith picture upload
			Log::debug ('now do upload stuff');
			$imageInfo = new ImageInfo();
			$userName = $userInfo->getUserName();
			$this->handlePicUpload ($userInfo->getId(), $userName, $imageInfo);
					
			$msg = 'INFO:The account has been created for ' .$userInfo->getUserName();
			parent::setErrorMessage ($msg);	
		}
		parent::setForm ($userInfo);	
		
		if ($userInfo->getId () != 0)
			$forwardTo .= '?id=' .$userInfo->getId ();
		
		Log::debug ('forwardTo:' .$forwardTo);
		$this->_redirect ($forwardTo);	
	}
	
    public function deleteAction ()
    {
    	Log::debug ('deleteAction');
    	$params = ServerParams::getQueryParameters();
    	$id = $params['id'];
    	if ($id != null)
    	{
    		Log::debug ('call delete');
    		UserManager::deleteUser ($id);
    	}
    	$this->_redirect ('/admin');
    }	
	
	/////////////////////////////////
	// 
	private function validateUserForm ($form)
	{
		if ($form == null)
			return 'Empty form';
		if ($form->get ("first_name") == null || $form->get("last_name") == null)
			return 'You must enter the names';
		if ($form->get ('phone') == null && $form->get('cell') == null)
			return 'You must enter the phone number';
		if ($form->get ('user_name') == null)
			return 'You must enter the user ID';
			
		return null; 
			
	}
	
	private function formToUserInfo ($form)
	{
		$userInfo = new UserInfo();
		$userInfo->setId ($form->get('id'));
		$userInfo->setFirstName ($form->get('first_name'));
		$userInfo->setLastName ($form->get('last_name'));
		
		$userInfo->setPhone ($form->get('phone'));
		$userInfo->setCell ($form->get('cell'));
		$userInfo->setEmail ($form->get('email'));
		$userInfo->setTitle ($form->get('title'));
		$userInfo->setAddress ($form->get('address'));
		$accountType = $form->get('account_type');
		$type = AccountTypes::getAccountTypeCode ($accountType);
		//Log::debug ('type:' .$accountType .', code=' .$type);
		$userInfo->setAccountType ($type);
		$userInfo->setRoleName ($form->get('role_name'));
		$userName = $form->get('user_name');
		$userInfo->setUserName ($userName);
		$userInfo->setDescription ($form->get('description'));
		$userInfo->setCreateTime (DatetimeUtil::getCurrentTimeStr());
		return $userInfo;
	}
	
	private function saveUser ($userInfo, $resetPassword)
	{
		$id = $userInfo->getId ();
		//if new user
		if ($id == 0)
		{
			$userName = $userInfo->getUserName();
			$password = Utils::hmac (GlobalConstants::PWD_SALT, $userName);
			$userInfo->setPassword ($password);
			$userInfo->setCreateTime (DatetimeUtil::getCurrentTimeStr());
			$oUserInfo= UserManager::getUserInfoByNames ($userInfo->getFirstName(), $userInfo->getLastName(), null);
			if ($oUserInfo != null)
			{
				$userInfo->setId ($oUserInfo->getId());
				$userInfo->setCreateTime ($oUserInfo->getCreateTime());
				$userInfo->setPasword ($oUserInfo->getPassword);
			}
		}
		else
		{
			if ($resetPassword)
			{
				$userName = $userInfo->getUserName();
				$password = Utils::hmac (GlobalConstants::PWD_SALT, $userName);
				$userInfo->setPassword ($password);
			}
			$oUserInfo = UserManager::getUserInfoById ($id);
			$userInfo->setCreateTime($oUserInfo->getCreateTime());
		}
		
		if ($userInfo->getId () != 0)
		{
			UserManager::updateUserInfo($userInfo);
		}
		else
		{
			UserManager::saveUserInfo ($userInfo);
		}
		
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
    		$oInfo = $db->getImageInfoByUserAndName ($userName, $imgInfo->getFile());
    		if ($oInfo != null)
    			$db->updateImageInfo ($imgInfo, false);
    		else
    			$db->saveImageInfo($imgInfo, false);
    	}
  
	}

}
?>