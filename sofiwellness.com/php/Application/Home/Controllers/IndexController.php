<?php
/*
 * $Id:$
 * FILE:IndexController.php
 * CREATE: May 14, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'HomeInclude.php';

class IndexController extends BaseController
{
	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(HOME_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_NAME;
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_HOME);
        $accountType = Utils::getAccountType();
        if (UserManager::isAdmin())
        //if ($accountType != null && AccountTypes::isAdmin($accountType))
        	$this->view->view_mode = 'admin';
	}	

    public function indexAction()
    {       	
    	Log::debug('Default_indexController::indexAction');
    	
        Log::debug ('accountType:' .Utils::getAccountType());
        
		//now show view
		echo $this->view->render('HomeView.php');
	}	
    
}
?>