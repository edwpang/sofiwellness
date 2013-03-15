<?php
/*
 * $Id: AuthPlugin.php,v 1.11 2009/10/06 19:44:09 gorsen Exp $
 * FILE:AuthPlugin.php
 * CREATE: Oct 6, 2008
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once APP_COMMON_DIR .'/Util/Log.php'; 
require_once APP_COMMON_DIR .'/Models/GlobalConstants.php'; 
require_once APP_COMMON_DIR .'/Util/SessionUtil.php'; 

 
class AuthPlugin extends Zend_Controller_Plugin_Abstract
{
	private $_allowModule = array (
		'default', 'auth', 'logout', 'resource', 'aboutus', 'contact', 'service'
	);
	
	private $_allowModCtrl = array (
		'info/terms',
		'info/privacy',
		'info/about',
		'appointment/index'
	);
	
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {   
    	//Log::debug ('##AuthPlugin::preDispatch');
        try 
        {         
            $request = $this->getRequest();
            $moduleName         = $this->getRequest()->getModuleName();
            $controllerName     = $this->getRequest()->getControllerName();
            $actionName         = $this->getRequest()->getActionName();
            $frontController    = Zend_Controller_Front::getInstance();
            //$user = Zend_Auth::getInstance()->getIdentity();
            $user = SessionUtil::get (GlobalConstants::USER_ID);
            Log::debug ('moduleName:' .$moduleName .', controllerName:' .$controllerName
                            . ', actionName:' .$actionName .', user=' .$user);
			if ($user != null)
				return true;
				
			//$ctrlName = null;
            //if ($controllerName != 'index')
            	$ctrlName = $controllerName;
            	
            //for admin data pulling
            //if ($moduleName == 'admin' && $ctrlName == 'dataproc' && $actionName == 'feeds')
            //	return true;
            	
            if(!$this->isNoLoginNeed($moduleName, $ctrlName)) 
            {
                Log::debug ('Need to login');
            	$this->redirectToLoginPage ($request);
            }
        }
        catch (Exception $e) 
        {
        	Log::error ($e->getMessage());
        }
        
        return true;
    }
    
    
    protected function isNoLoginNeed ($moduleName, $actionName)
    {
    	Log::debug ('isNoLoginNeed:moduleName:' .$moduleName .', actionName:'.$actionName);
    	foreach ($this->_allowModule as $module)
    	{
    		if ($moduleName == $module)
    		{
    			Log::debug ('No login required for :' .$moduleName);
    			return true;	
    		}
    	}
    	
    	if ($actionName != null)
    	{
    		$name = $moduleName .'/' .$actionName;
    		Log::debug ('isNoLoginNeed:name=' .$name);
	    	foreach ($this->_allowModCtrl as $module)
	    	{
	    		Log::debug ('module:' .$module);
	    		if ($name == $module)
	    		{
	    			Log::debug ('No login required for :' .$name);
	    			return true;	
	    		}
	    	}
    	}
    	
    	return false;
    }
    
    
    protected function redirectToLoginPage ($request)
    {
    	//$request->setModuleName('default');
        //$request->setControllerName('index');
        //$request->setActionName('index');
    	$request->setModuleName('auth');
        $request->setControllerName('login');
        $request->setActionName('index');
    }

	protected function redirectToGroupLoginPage ($request)
    {
    	$request->setModuleName('wiki');
        $request->setControllerName('group');
        $request->setActionName('index');
    } 
}
?>