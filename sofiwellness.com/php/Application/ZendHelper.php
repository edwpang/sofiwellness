<?php
/*
 * $Id:$
 * FILE:ZendHelper.php
 * CREATE: May 2, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */

//require_once 'app_constants.php';

class ZendHelper
{
	static function loadClasses($classes) 
	{
		 $iNumOfClass = sizeof($classes);
		 for ( $counter = 0; $counter <= $iNumOfClass; $counter += 10) 
			Zend_Loader::loadClass($classes[$counter]);
	}

	static function loadClass($class) 
	{
		Zend_Loader::loadClass($class);
	}	
	
	static function isRegistered($key) 
	{
  		return Zend_Registry::isRegistered($key);
	}
	static function getFromRegistry($key) 
	{
		return Zend_Registry::get($key);
	}
	
	static function setToRegisty($key, $objectToSave) 
	{
		Zend_Registry::set($key, $objectToSave);
	}
	 	/**
 	 * In Zend Framework, the way to get Session Name space is not consistant.
 	 * This method hide the difference.
 	 */
 	static function getSession($nameSpace) 
 	{ 	
        $namedSession = new Zend_Session_Namespace('Zend_Auth');
 		return $namedSession;
 	}
 	
 	static function startSession() 
 	{
		Zend_Session::start();
 	}	
 	
 	
 	static function loadAppConfig() 
 	{
 		$appConfig = new Zend_Config_Ini(APPCONFIG_INI_ABS_PATH, DEPLOYMENT_TYPE);	
		return $appConfig;
 	}
 	
 	static function getLogger($logName)
 	{
 		$loggerRegisted = Zend_Registry::isRegistered($logName); 
 		if ($loggerRegisted)
 			$logger = Zend_Registry::get($logName);
 		else
 		{
 			$logger =& LoggerManager::getLogger($logName);
 			Zend_Registry::set($logName, $logger);
 		}
 		
 		return $logger;
 	}
 	
 	
   	static function getAppDb() 
   	{
  		$isRegistered = ZendHelper::isRegistered(DB_APP);
 		if ($isRegistered) 
 			$dbConn = ZendHelper::getFromRegistry(DB_APP);
 		else 
 		{
 			$type = DB_APP_TYPE;
		 	$dbparams = array(
			    'host' =>     DB_APP_HOST,
				'username' => DB_APP_USERNAME,
				'password' => DB_APP_PASSWORD,
				'dbname' =>   DB_APP_DBNAME
			);
			$dbConn = Zend_Db::factory($type, $dbparams);
			ZendHelper::setToRegisty(DB_APP, $dbConn);
 		}
 		$logger = ZendHelper::getLogger(Log::LOG_NAME);
 		//$logger->info ('connect to DB:' .DB_APP_DBNAME);
 		return $dbConn;
 	}		 	
}
?>