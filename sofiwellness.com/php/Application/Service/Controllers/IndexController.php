<?php
/*
 * $Id:$
 * FILE:IndexController.php
 * CREATE: Jun 18, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'ServiceInclude.php';

class Service_IndexController extends BaseController
{

	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(SERVICE_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_TITLE .':Services&Fee';
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_SERVICE);
	}

    public function indexAction()
    {
    	//Log::debug ('###Service::indexAction');
     	//get listService from db
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
     	//if (Utils::getUserId () != null && Utils::getAccountType () == AccountTypes::ADMINISTRATOR)
     	if (UserManager::isAdmin())
     		$this->view->mode = 'edit';
     	//Log::debug ('view mode:' .$this->view->mode);
    	echo $this->view->render('ServiceView.php');
    }
    
    public function saveAction()
    {
    	Log::debug ('ServiceIndexController::saveAction');
    	$form = Utils::getSimpleFormData ();
    	
    	$id = $_POST['id'];
   		$output = $_POST['value'];
    	//Log::debug ('id:' .$id .', value:' .$output);
    	echo $output;
    	//$this->_redirect('/service');
    }
    
    /////////////////////////////////////////////////////
    //
    private function getServiceItems ($language=null)
    {
    	if ($language == null)
    		$language = GlobalConstants::ENGLISH;
    	$db = new ServiceItemDbAccessor ();
    	$list = $db->getServiceItemsByLanguage ($language);
    	if ($list != null && $list->size() > 0)
    		return $list;
    		
    	//OK, now get default and save to db
    	$list = $this->loadDefServiceConfig ($language);
    	if ($list == null || $list->size() == 0)
    		return $list;
    		
    	$count = $list->size ();
    	for ($i = 0; $i < $count; $i++)
    	{
    		$info = $list->get($i);
    		$db->save($info);	
    	}
    	
    	return $list;
    }
    
    private function loadDefServiceConfig ($language=null)
    {
    	//for chinese text it is service_list_zh.ini
    	if ($language == null)
    		$language = GlobalConstants::ENGLISH;
    	
    	$fileName = 'service_list.ini';
    	if ($language == GlobalConstants::CHINESE)
    		$fileName = 'service_list_zh.ini';
    	$file = GlobalConstants::getSourceDataRealPath() .'/' .$fileName;	
    	$config = IniConfigUtil::getInstance($file); 
    	$settings = $config->getSettings();
    	//Log::debug ('size:' .count ($settings));
    	$list = new ArrayList ();
    	$i = 1;
    	foreach($settings as $secName => $section) 
        { 
        	//Log::debug ('section:' .$secName);
        	$info = new ServiceItemInfo();
        	$info->setName ($secName);
        	foreach ($section as $name => $value)
        	{
         		//Log::debug ($name . '=' .$value);
         		if ($name == 'title')
         			$info->setTitle ($value);
         		else if ($name == 'description')
         			$info->setDescription ($value);
         		else if ($name == 'image')
         			$info->setImageRef ($value);
        	}
        	//add other info
        	$info->setLanguage ($language);
        	$info->setCreateTime (DatetimeUtil::getCurrentTimeStr());
        	$info->setListOrder ($i);
        	$list->add ($info);
        	$i++;
        } 
        return $list;
    }
    
}
?>