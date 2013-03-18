<?php
/*********************************************************************************
 * $Id: env_constants.php.tmpl,v 1.9 2009/07/08 20:22:39 gorsen Exp $
 * Created on 2007-2-24 13:01:50
 * @author steven
 *
 * All parameters need to be verified to make sure they are valid in new environment.
 **********************************************************************************/

 /* 'APP_ROOT' is the root folder of this php project.
  * It is the parent folder for htdocs, library, applications etc.
  * Many absolute pathes will be constructed based on this root folder
  *  in constants.php, and other files.
  */
  
if (stristr (PHP_OS, 'WIN')) { 
 // Win 
 define('APP_ROOT', 'd:/website/sofiwellness.com/php');
 define('LIBRARY_DIR', 'd:/website/sofiwellness.com/library'); 
 define('OS_PATH_DELI', ';');  
} else { 
  define('APP_ROOT', '/home1/greenhi9/php');
  define('LIBRARY_DIR', '/home1/greenhi9/library');
  define('OS_PATH_DELI', ':');  //for linux
}


define('APP_MAIN_PATH', APP_ROOT .'/Application');


//--------------------------------------------------
// Cache folders
//--------------------------------------------------

define ('CACHE_MAIN_FOLDER', APP_ROOT .'/var/cacheDir');

define('LOCAL_FILE_CACHE_DIR', CACHE_MAIN_FOLDER .'/filecache');
define('CACHE_LONGTERM', 	CACHE_MAIN_FOLDER . '/longterm');
define('CACHE_MIDTERM', 	CACHE_MAIN_FOLDER . '/midterm');
define('CACHE_SHORTTERM', 	CACHE_MAIN_FOLDER . '/shortterm');

define('OPEN_ID_CACHE', 	CACHE_MAIN_FOLDER . '/openid');

//feed catch
define('CACHE_FEED', 	CACHE_MAIN_FOLDER . '/feed');

//for mapie rss feed cache
define('MAGPIE_CACHE_DIR', CACHE_FEED);

//for data source populate to database
define ('SOURCE_DATA', APP_ROOT.'/data');

//for group data

define ('USER_DATA', APP_ROOT .'/user_data');


// ---------------------------------------------------
// folder for css and image, font file
//----------------------------------------------------

define ('CSS_FOLDER', '/css');
define ('CSS_IMAGE_FOLDER', '/css/images');
define ('JS_FOLDER', '/js');

define ('IMAGE_FOLDER', '/images');
define ('IMAGE_SYS_FOLDER', IMAGE_FOLDER .'/sys');
define ('IMAGE_AVATAR_FOLDER', '/images/avatar');


//for windows, just empty is OK.
define ('FONT_FILE_FOLDER', '');
define('CAPTCHA_FONT_NAME', 'arial.ttf');


//for tmp upload
define ('TMP_IMPORT_FOLDER', '/images/avatar');

 //-----------------------------------------------------------------------------------
 // Deployment
 //------------------------------------------------------------------------------------
 /* 'DEPLOYMENT_TYPE' tells Zend Config which section in the appconfig.ini will be load.
  * in dev environment, use 'dev'. In producion, use 'produciton'
  */

 define('DEPLOYMENT_TYPE', 'dev2');
 //define('DEPLOYMENT_TYPE', 'production');


//----------------------------------------------------------------
// configures
//------------------------------------------------------------------

 /* disable all cache, tuning purpose
  * remember to config conf/cache_config.php */
 define('APP_CACHE_DISABLED', false);
 define('APP_FORCE_UPDATE_CACHE', false);



//------------------------------------------------------------------------
// Logging
//--------------------------------------------------------------------
 /* For performance reason, check this constants first
  *  before invoke log.debug method.
  *
  * Although logging level is defined in log4php.ini, checking
  * whether it is debug enable will have performance advantage
  */
 /* 'LOG4PHPDEBUG' and 'LOG4PHPDEBUGSQL' are deprecated*/
 define('LOG4PHPDEBUG',    false);
 define('LOG4PHPDEBUGSQL', false);

 define('LOG_INFO_FG',       true);
 define('LOG_DEBUG_FG',      true);
 define('LOG_DEBUGSQL_FG',   true);
 define('LOG_ACTIVITY_FG',   true);
 define('LOG_JS_FG',         true);

 define ('LOGGER_NAME', 'App');
 define ('LOG_DEBUG', true);

 /* turn to true if log4php is not working properly to check info */
 define('ZENDLOG_CONF_INFO_FG', false);

/*  email - smtp settings */
define('SMTP_HOST', 'smtp.broadband.rogers.com');
define('SMTP_AUTH_REQUIRED', true);
/* if SMTP_AUTH_REQUIRED is true, the following authentication information are required */
define('SMTP_AUTH_USER', 'smtp_user_name');
define('SMTP_AUTH_PASS', 'smtp_user_password');

define('EMAIL_SENDER', 'system@greenhillhab.com');

//-------------------------------------------------
// db
//------------------------------------------------
//for database
define ('DB_APP_TYPE', 'PDO_MYSQL');
define ('DB_APP_HOST', 'sofiwellness.com');  //or xxx.com
//for source1
define ('DB_APP_USERNAME', 'greenhi9_prog');
define ('DB_APP_PASSWORD', '123456');
define ('DB_APP_DBNAME', 'greenhi9_source1');



?>