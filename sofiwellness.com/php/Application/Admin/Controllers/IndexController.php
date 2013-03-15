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
require_once 'AdminInclude.php';

class Admin_IndexController extends BaseController
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
    	Log::debug('Admin::indexAction');
    	$userId = Utils::getUserId ();
    	
    	if ($userId == null ||!UserManager::isAdmin ())
    	{
    		//to login
    		parent::setLastUrl ('/admin');
    		$this->_redirect('/auth/user/login');	
    		return;
    	}
        
        $urlEdit = '/admin/edit?';
        $urlDel = '/admin/edit/delete?';
        $itemList = $this->getProfessionalTableItemList ($urlEdit, $urlDel);
        Log::debug ('list size:' .$itemList->size());
        parent::setupViewCommParams ($itemList);
        
		//now show view
		parent::setRightPanel ('user_list_panel.php');
		echo $this->view->render('AdminView.php');
	}	
	
	
	/////////////////////////////////////////
	//private
	
	private function getProfessionalTableItemList ($urlEdit, $urlDel)
	{
		$itemList = null;
		$list = UserManager:: getUserInfoList (AccountTypes::PROFESSIONAL);	
		if ($list != null)
		{
			$itemList = new ItemList ();
			$count = $list->size();
			for ($i = 0; $i < $count; $i++)
			{
				$info = $list->get($i);
				$item = new Item ();
				$item->setId ($info->getId());
				$rowList = new ItemList ();
				$item->setSubItemList ($rowList);
				$itemName = new Item();
				$itemName->setName ($info->getFirstName() .' ' .$info->getLastName());
				$href = $urlEdit .'id=' .$info->getId ();
				$itemName->setHref ($href);
				$rowList->add ($itemName);
				$itemAct = new Item();
				$itemAct->setName ('delete');
				$hrefDel = $urlDel .'id=' .$info->getId();
				$itemAct->setHref ($hrefDel);
				$rowList->add ($itemAct);
				
				
				$itemList->add ($item);
			}
		}
		
		return $itemList;
	}	
	
	private function getProfessionalList ($url)
	{
		$itemList = null;
		$list = UserManager:: getUserInfoList (AccountTypes::PROFESSIONAL);	
		if ($list != null)
		{
			$itemList = new ItemList ();
			$count = $list->size();
			for ($i = 0; $i < $count; $i++)
			{
				$info = $list->get($i);
				$item = new Item ();
				$item->setId ($info->getId());
				$item->setName ($info->getFirstName() .' ' .$info->getLastName());
				$href = $url .'id=' .$info->getId ();
				$item->setHref ($href);
				$itemList->add ($item);
			}
		}
		
		return $itemList;
	}
}
?>