<?php
/*
 * $Id:$
 * FILE:SignupController.php
 * CREATE: Jun 3, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AuthInclude.php';

class Auth_SignupController extends BaseController
{
	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(AUTH_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_NAME;
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_HOME);
	}	
	
    public function indexAction()
    {
    	$userInfo = parent::getForm();
    	if ($userInfo == null)
    		$userInfo = new UserInfo();
    		    	
    	$this->view->error_message = parent::getErrorMessage();
    	$this->view->form_url = '/auth/signup/save';
    	$this->view->user_info = $userInfo;
    	parent::setViewMode ('signup');
    	parent::setRightPanel('signup_form.php');    
       	echo $this->view->render('AuthView.php');
    }
    
    public function saveAction ()
    {
    	$forwardTo = '/home';  //to welcome?
    	$form = Utils::getSimpleFormData();
    	$userInfo = $this->formToUserInfo ($form);	
    	//validate
    	$err = $this->validate($userInfo);
    	
    	if ($err != null)
    	{
    		parent::setErrorMessage ($err);
    		parent::setForm ($userInfo);
    		$forwardTo = '/auth/signup';	
    	}
    	else
    	{
    		$phone = ValidateUtil::toPhoneFormat($userInfo->getPhone());
    		$userInfo->setPhone($phone);
    		
    		//check if user already has account
    		$oInfo = UserManager::getUserInfoByNames ($userInfo->getFirstName(), 
    				$userInfo->getLastName(), $userInfo->getPhone());
			if ($oInfo != null)
			{
				//just update
				$userInfo->setId ($oInfo->getId());
				UserManager::updateUserInfo ($userInfo);
			}
			else    		
    			UserManager::saveUserInfo ($userInfo);	
    			
    		//signor just sign up
    		SessionUtil::set (GlobalConstants::JUST_SIGNUP, 'true');
    		
    		//now login
			$userId = $userInfo->getId ();
            SessionUtil::set (GlobalConstants::USER_NAME, $userInfo->getUserName());
			SessionUtil::set (GlobalConstants::USER_ID, $userId);
		    SessionUtil::set (GlobalConstants::USER_INFO, $userInfo);
		    SessionUtil::set (GlobalConstants::ACCOUNT_TYPE, $userInfo->getAccountType());
		    UserManager::updateLastVisitTime ($userId);    		
    	}
    	
    	$this->_redirect ($forwardTo);		
    }
    
    
    ////////////////////////////////////////////
    //private
    
    private function formToUserInfo ($form)
	{
		$userInfo = new UserInfo();
		$userInfo->setFirstName ($form->get('first_name'));
		$userInfo->setLastName ($form->get('last_name'));
		$userInfo->setPhone ($form->get('phone'));
		$userInfo->setEmail ($form->get('email'));
		$userInfo->setAddress ($form->get('address'));
		$userInfo->setAccountType (AccountTypes::CUSTOMER);
		$userName = $form->get('user_name');
		$userInfo->setUserName ($userName);
		$password = $form->get ('pwd');
		if ($password == null)
		{
			$password = $form->get ('password');
			if ($password != null)
				$password = Utils::hmac (GlobalConstants::PWD_SALT, $password);
		}
		$userInfo->setPassword ($password);
		$userInfo->setCreateTime (DatetimeUtil::getCurrentTimeStr());
		return $userInfo;
	}
	
	private function validate ($userInfo)
	{
		$err = '';
		if ($userInfo->getLastName () == null)
			$err .= 'Last Name,';
		if ($userInfo->getPhone () == null)
			$err .= ' Phone,';
		else
		{
			if (!ValidateUtil::validatePhoneNumber($userInfo->getPhone ()))
    			$err .= ' Invalid phone number,';		
		}
		if ($userInfo->getUserName () == null)
			$err .= " User ID,";
		if ($userInfo->getPassword () == null)
			$err .= ' Password.';
			
		
		if (strlen ($err) > 2)
		{
			return  'The required fields are empty:' . $err;	
		}	
		
		//now check user name
		if (UserManager::hasUserName ($userInfo->getUserName()))
		{
			return 'The user name has been taken, please use another name';	
		}
		
		return null;
	}
}
?>
