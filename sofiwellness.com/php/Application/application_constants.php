<?php
/* $Id: application_constants.php,v 1.4 2008/10/04 00:59:56 gorsen Exp $
 * Created on 2007-2-24 23:07:34
 * @auther steven
 * These constants are used in the application.
 *
 * Unlike those constants defined in constants.php, constants defined
 * in this file are not environment related. They may need to be modified
 * at development time. But they should never be modified in deployment
 * time.
 */
 
//RUN_MODE : dev or prod - just for switch stylesheet and other things for convinient
//it can depend on the DEPLOYMENT_TYPE in env_constants, here easy to test
if (DEPLOYMENT_TYPE == 'production')
	define ('RUN_MODE', 'prod');
else
	define('RUN_MODE', 'dev');


 /* each module will have its own session space */
 define('ZEND_SESSION_SPACE', 'spike_auth');
 define('SESSION_SPACE_FAVORITE', 'spike_favorite');

 /** Used for save and retried appconfig in registry */
 define('KEY_APPCONFIG', 'key_appconfig');

 define('KEY_AUTH_DB', 'key_auth_db');
 define('KEY_BOOKMARK_DB', 'key_bookmark_db');
 
 define('KEY_DB_USER', 'key_db_user');
 define('KEY_DB_APP', 'key_db_app');


 define('KEY_FILTER_GET', 'fGet');
 define('KEY_FILTER_POST', 'fPost');
 /*Logger Names, log4php
  * also refer to 'LOG4PHPDEBUG' and 'LOG4PHPDEBUGSQL'
  *  in env_constants.php */
 define('ACTIVITY_LOG',  'ActivityLogger');
 define('INFO_LOG',      'InfoLogger');
 define('ERROR_LOG',     'ErrorLogger');
 define('DEBUG_LOG',     'DebugLogger');
 define('DEBUG_SQL_LOG', 'DebugSQLLogger');
 define('ROOT_LOG',      'RootLogger');
 /* JS_LOG is for logging the client-side javascript logging*/
 define('JS_LOG',        'JSLogger');

 /* session keys */
 define('KEY_USER_LOCALE', 'fUserLocale');
 define('KEY_USER_TIMEZONE', 'fUserTimeZone');
 define('KEY_USER_REGISTERED', 'fUserIsRegistered');
 define('KEY_USER_LOGGED_IN', 'fUserIsLoggedIn');
 /* user primary key */
 define('KEY_USER_PK', 'fUserPK');

 /* Cache Keys  */

 /* Where to save appconfig, Registry, or local file cache? Local Cache should be faster */
 define('SAVE_APPCONFIG_IN_REGISTRY', false);
 /* FAVORITE_LIK? are used to concatinate the link to for bookmark.
  * server domain are externalized in appconfig.ini
  */


/* Cache setup */
?>
