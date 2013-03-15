<?php
/*
 * $Id:$
 * FILE:IndexController.php
 * CREATE: May 16, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AuthInclude.php';

class Auth_IndexController extends BaseController
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
    	Log::debug('Auth::indexAction');
    	
    	$this->view->error_message = SessionUtil::get (GlobalConstants::ERROR_MESSAGE);	
		if ($this->view->error_message != null)
			SessionUtil::remove (GlobalConstants::ERROR_MESSAGE);	
			
		if (SessionUtil::has (GlobalConstants::RETURN_URL))
		{
			$this->view->on_success = SessionUtil::get (GlobalConstants::RETURN_URL);
			SessionUtil::remove (GlobalConstants::RETURN_URL);
		}
		
		//now show view
		parent::setRightPanel('login_form.php');
		echo $this->view->render('AuthView.php');
	}	
	
	
	public function loginAction ()
	{
		Log::debug ('###Login');
		$form = Utils::getSimpleFormData();
		$userName = $form->get('username');
		$password = $form->get('password');
		$forwardTo = '/auth';
		$userInfo = $this->validateUser ($userName, $password);
		if ($userInfo != null)
		{ 
			$forwardTo = $form->get('on_success');;
		}
		else
		{
			$err = 'Invalid user name or password';
			SessionUtil::set (GlobalConstants::ERROR_MESSAGE, $err);	
		}
		$this->_redirect ($forwardTo);	
	}
	
	///////////////////////////////////////////////
	//
	private function validateUser ($userName, $password)
	{
		return null;
	}
}
?>
