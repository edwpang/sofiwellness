<?php
/* $Id: UserController.php,v 1.13 2009/04/24 19:31:22 gorsen Exp $
 * Created on 2007-2-13, 22:24:21
 * @author steven
 */

require_once 'AuthInclude.php';



class Auth_UserController extends BaseController
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
     
    }

    public function loginAction()
    {
    	Log::debug ('loginAction');   
    	$this->view->error_message = parent::getErrorMessage(true);	
			
		if (parent::getLastUrl(false))
		{
			$this->view->on_success = parent::getLastUrl();
		}
		
		//now show view
		parent::setRightPanel('login_form.php');    
       echo $this->view->render('AuthView.php');
    }
    
    public function authenicateAction ()
    {
    	Log::debug ('authenicateAction');
		$form = Utils::getSimpleFormData();
		$userName = $form->get('username');
		$password = $form->get('pwd');
		if ($password == null)
		{
			$password = $form->get('password');
			$password = Utils::hmac (GlobalConstants::PWD_SALT, $password);
		}
		$forwardTo = '/auth';
		$userInfo = $this->validateUser ($userName, $password);
		if ($userInfo != null)
		{ 
			Log::info ($userName .' has logined');
			$userId = $userInfo->getId ();
            SessionUtil::set (GlobalConstants::USER_NAME, $userName);
			SessionUtil::set (GlobalConstants::USER_ID, $userId);
		    SessionUtil::set (GlobalConstants::USER_INFO, $userInfo);
		    SessionUtil::set (GlobalConstants::ACCOUNT_TYPE, $userInfo->getAccountType());
		    UserManager::updateLastVisitTime ($userId);
			$forwardTo = $form->get('on_success');;
			if ($forwardTo == null)
				$forwardTo = '/home';
		}
		else
		{
			$err = 'Invalid user name or password';
			SessionUtil::set (GlobalConstants::ERROR_MESSAGE, $err);	
		}
		$this->_redirect ($forwardTo);	
    		
    }
    

    public function logoutAction()
    {
        //echo 'This is the logoutAction.';
		Log::debug ('###logout');
		$userId = Utils::getUserId ();
		SessionUtil::remove (GlobalConstants::USER_ID);
		SessionUtil::remove (GlobalConstants::USER_INFO);
		SessionUtil::remove (GlobalConstants::USER_NAME);
		session_unset();
 		session_destroy();
		$redirectTarget = '/';
       	$this->_redirect($redirectTarget);
    }
    
	////////////////////////////////////////////////////
	//private functions
	
	private function validateUser ($userName, $password)
	{
		if ($userName == null)
			return null;
			
		Log::debug ('userName:' .$userName .', password:' .$password);
		$info = UserManager::getUserInfo ($userName, $password);
		if ($info == null)
		Log::debug ('info = null');
		return $info;
	}

}
?>