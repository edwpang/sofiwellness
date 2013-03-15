<?php
/*
 * $Id:$
 * FILE:IndexController.php
 * CREATE: Jun 7, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'ContactInclude.php';


class Contact_IndexController extends BaseController
{

	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(CONTACT_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_TITLE .':' .GlobalConstants::CONTACT_US;
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_CONTACT_US);
	}

    public function indexAction()
    {
    	Log::debug ('###ContactIndex::indexAction');
    	
    	echo $this->view->render('ContactView.php');
    }
    
}
?>
