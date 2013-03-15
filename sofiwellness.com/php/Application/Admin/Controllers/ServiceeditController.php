<?php
/*
 * $Id:$
 * FILE:ServiceeditController.php
 * CREATE: Jun 22, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AdminInclude.php'; 
require_once APP_APPOINTMENT_DIR .'/Controllers/AppointmentInclude.php';
require_once APP_SERVICE_DIR .'/Controllers/ServiceInclude.php';
 
class Admin_ServiceeditController extends BaseController
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
   
      	if (!UserManager::isAdmin ())
    	{
    		//to login
    		parent::setLastUrl ('/admin');
    		$this->_redirect('/auth/user/login');	
    		return;
    	}
    	
    	
   		$listService = $this->getServiceItems(null);
    	//replace line break with br
    	if ($listService != null)
    	{
    		$count = $listService->size();
    		for ($i = 0; $i < $count; $i++)
    		{
    			$info = $listService->get($i);
    			$desc = Utils::replaceLineBreakWithHtmlTags ($info->getDescription());
    			$info->setDescription ($desc);	
    		}
    	}
     	$this->view->source_list = $listService;
     	$this->view->mode = 'view';
     	if (Utils::getUserId () != null && Utils::getAccountType () == AccountTypes::ADMINISTRATOR)
     		$this->view->mode = 'edit';
    	
		echo $this->view->render('ServiceListView.php');
     }

    public function formAction()
    {
    	Log::debug ('ServiceeditController::formAction');
    	
    	if (!UserManager::isAdmin ())
    	{
    		//to login
    		parent::setLastUrl ('/admin');
    		$this->_redirect('/auth/user/login');	
    		return;
    	}
    	
    	
    	$params = ServerParams::getQueryParameters();
    	$id = $params['id'];
    	
    	$this->view->service_item = $this->getServiceItem($id);
    	
    	parent::setLeftPanel (null, '8%');
    	parent::setRightPanel ('service_edit_form.php', '90%');
		echo $this->view->render('AdminView.php');
    }
    
    public function saveAction ()
    {
    	Log::debug ('ServiceeditController::save');
    	$form = Utils::getSimpleFormData();
    	$info = $this->formToServiceItemInfo($form);
    	$db = new ServiceItemDbAccessor();
    	//get old one
    	$oInfo = $db->getServiceItemInfoById ($info->getId());
    	$oInfo->setTitle ($info->getTitle());
    	$oInfo->setDescription ($info->getDescription());
    	Log::debug ('desc:' .$oInfo->getDescription());
    	$db->updateServiceItemInfo($oInfo);
    	//$db->save ($oInfo);
    	$this->_redirect('/admin/serviceedit');	
    }
    
    ///////////////////////////////////////////////////////
    //
    private function getServiceItem($id)
    {
    	$db = new ServiceItemDbAccessor();
    	return $db->getServiceItemInfoById($id);	
    }
    
    private function getServiceItems ($language=null)
    {
    	if ($language == null)
    		$language = GlobalConstants::ENGLISH;
    	$db = new ServiceItemDbAccessor ();
    	$list = $db->getServiceItemsByLanguage ($language);
 	
    	return $list;
    }
    
    private function formToServiceItemInfo ($form)
    {
    	$info = new ServiceItemInfo ();
    	$info->setId ($form->get('id'));
    	$info->setLanguage ($form->get('language'));
    	$info->setTitle ($form->get('title'));	
    	$info->setDescription ($form->get('description'));
    	return $info;
    }
}
?>
