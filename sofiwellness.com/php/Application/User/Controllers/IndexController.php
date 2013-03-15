<?php
/*
 * $Id:$
 * FILE:IndexController.php
 * CREATE: Jun 25, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'UserInclude.php';

class User_IndexController extends BaseController
{

	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(USER_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_TITLE .':User';
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_USER);
	}

	//default to user search form
    public function indexAction()
    {
    	$params = ServerParams::getQueryParameters();
    	$id = $params['id'];
    	$form = parent::getForm();
    	if ($form == null)
    		$form = new UserInfo();
    	$this->view->user_info = $form;
    	parent::setRightPanel ('user_search_form.php');
    	echo $this->view->render('UserView.php');
    }
    
    public function searchAction ()
    {
    	$form = Utils::getSimpleFormData();
    	$userInfo = $this->formToUserInfo($form, true);
    	$listUserInfo = UserManager::getUserInfoByParams ($userInfo);
    	if ($listUserInfo != null)
    	{
    	Log::debug ('listUserInfo size:' .$listUserInfo->size());
    		if ($listUserInfo->size () == 1)
    		{
    			$info = $listUserInfo->get(0);
    			$this->_redirect ('/user/index/edit?id='.$info->getId());	   
    			return;		
    		}
    	}
    	else
    	{
    		//back to search	
    		parent::setForm($userInfo);
    		parent::setErrorMessage ('Could not find any user matched with the search criteria.');
    		$this->_redirect ('/user');	   	
    		return;
    	}
    	$this->view->user_list = $this->getUserItemList($listUserInfo, '/user/index/edit?');
    	parent::setRightPanel ('user_search_list.php');
    	echo $this->view->render('UserView.php');
    }
    
    public function editAction ()
    {
    	$params = ServerParams::getQueryParameters();
    	$id = $params['id'];
    	$info = UserManager::getUserInfoById ($id);
    	$this->view->user_info = $info;
    	$this->view->status_array = UserManager::getStatusArray();
        parent::setRightPanel ('user_form.php');
    	echo $this->view->render('UserView.php');
    }
    
    public function saveAction ()
    {
    	$form = Utils::getSimpleFormData();
    	$userInfo = $this->formToUserInfo($form);
    			$err = $this->validateUserForm ($form);
		if ($err != null)
		{
			Log::error ($err);
			parent::setErrorMessage ($err);		
			parent::setForm ($userInfo);
			$this->_redirect ('/user/index/edit?id='.$userInfo->getId());	  
			return; 
		}
		
		
    	if ($userInfo->getId () != 0)
    	{
    		$oInfo = UserManager::getUserInfoById($userInfo->getId());
    		$userInfo->setCreateTime ($oInfo->getCreateTime());
    		$userInfo->setRoleName ($oInfo->getRoleName());
    		//if reset password?
    		if ($form->get('re_password') != null)
    		{
				$userName = $userInfo->getUserName();
				$password = Utils::hmac (GlobalConstants::PWD_SALT, $userName);
				$userInfo->setPassword ($password);
    		}
    		else
    			$userInfo->setPassword ($oInfo->getPassword());
			UserManager::updateUserInfo ($userInfo);
    	}
    	else
    	{
    			
    	}
    	$this->_redirect ('/user');	   	
    }
    
    //////////////////////////////////////////////////
    // private
    
    private function formToUserInfo($form, $isSearch=false)
    {
    	$info = new UserInfo();
    	$info->setFirstName ($form->get('first_name'));
    	$info->setLastName ($form->get('last_name'));
    	$info->setPhone ($form->get('phone'));
    	$info->setEmail($form->get('email'));
    	if (!$isSearch)
    	{
			$info->setId ($form->get('id'));
			$info->setCell ($form->get('cell'));
			$info->setTitle ($form->get('title'));
			$info->setAddress ($form->get('address'));
			$accountType = $form->get('account_type');
			$type = AccountTypes::getAccountTypeCode ($accountType);
			$info->setAccountType ($type);
			$userName = $form->get('user_name');
			$info->setUserName ($userName);
			$info->setDescription ($form->get('description'));
			$info->setStatus ($form->get('status'));
			$info->setCreateTime (DatetimeUtil::getCurrentTimeStr());
    	}
    	return $info;
    }
    
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
    
    //list is the list of UserInfo return ItemList
    private function getUserItemList($list, $url)
    {
    	if ($list == null)
    		return null;
    		
    	//check if admin or not
    	$excludeAdmin = true;
    	if (UserManager::isAdmin())
    		$excludeAdmin = false;
    		
    	$itemList = new ItemList();
    	$count = $list->size();
    	for ($i = 0; $i < $count; $i++)
    	{
    		$info = $list->get($i);
    		if ($excludeAdmin && RoleNames::isAdmin($info->getRoleName))
    			continue;
    			
    		$item = new Item ();
    		$item->setId ($info->getId ());
    		$href = $url .'id=' .$info->getId ();
    		$item->setHref ($href);
    		$desc = '<span class="bold">';
    		$desc .= $info->getFirstName();
    		$desc .= ' ' .$info->getLastName();
    		$desc .= '</span>:';
    		if ($info->getPhone() != null)
    			$desc .= ' ' .$info->getPhone();
    		else if ($info->getCell() != null)
    			$desc .= '<span class="marl2">' .$info->getCell() .'</span>';
    		if ($info->getEmail() != null)
    			$desc .= '<span class="marl2">'  .$info->getEmail().'</span>';	
    		$item->setDesc ($desc);
    		$itemList->add ($item);
    	}
    	return $itemList;
    }
    
}
?>