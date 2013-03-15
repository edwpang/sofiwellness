<?php
/* $Id: constants.php,v 1.8 2009/01/26 14:04:06 gorsen Exp $
 * Created on 2007-2-24 2:28:27
 * @author steven
 *
 */
 /* *********************************************************************
  * Please try NOT to modify this file before look at env_constants.php.
  * In most cases, this constants file needs NO modification.
  *
  * In very few cases, this file needs modification, i.e:
  *   Moving to new version of libraries, like new Zend Framework, log4php
  *   Add new libraries to the include_path
  ***********************************************************************/
 require_once 'env_constants.php';

 define('APPLICATION_NAME', 'source1');
 /* define file names with absolute path to
  *    appconfig.ini, session.ini
  */
 define('CONF_DIR', APP_MAIN_PATH . '/conf');
 define('APPCONFIG_INI_ABS_PATH', CONF_DIR . '/appconfig.ini');
 /* config file for log4php, log4php is using this constant */
 define('LOG4PHP_CONFIGURATION', CONF_DIR . '/log4php.ini');
 /* SESSION_INI_ABS_PATH may not work */
 define('SESSION_INI_ABS_PATH',   CONF_DIR . '/session.ini');

 /* vvvvvvvvvvvvvvvvvvvvvvvvvvv include path vvvvvvvvvvvvvvvvvvvvvvvvvvvvv */
 /* prepare elements for include_path */

 /* common php folder for include */
 define('APP_COMMON_DIR', APP_ROOT . '/Application/Common');

 // plugin folder
 define ('APP_PLUGIN_DIR', APP_ROOT .'/Application/Plugin');

 // RSS Feed lib folder
// define('LIB_RSS_FEED_DIR', APP_ROOT .'/library/magpierss-0.72');


 /* application php source folder */
 define('APP_SRC_DIR',  APP_ROOT . '/Application/src');
  /* construct absolute path to Zend FW's dirs' */
  /* ZF_VERSION is for debug purpose when transist from one ZF version to the next.
   *  It may be removed later. Do not rely on this constant.
   */

 //define('ZF_VERSION', '1.12.2'); //This is for debug purpose.
 define('ZF_VERSION', '1.0.1'); //This is for debug purpose.
 define('ZEND_FW_DIR',  LIBRARY_DIR . '/ZendFramework-' . ZF_VERSION);
 define('ZEND_LIB_DIR1', ZEND_FW_DIR . '/library');
 define('ZEND_LIB_DIR2', ZEND_FW_DIR . '/incubator/library');
 /* path to log4php, log4php also use this constant */
 define('LOG4PHP_DIR', LIBRARY_DIR  . '/log4php-0.9/src/log4php');

 ///////////////////////////////////////////////////////////////////
 // third party libs - rss, openid and so on




 //////////////////////////////////////////////


 /* TODO, seems not required, remove this later. path to Controllers */
 //define('TOP_CTRLER_PATH', APP_ROOT . '/Application/Controllers');
 /* constructing include_path */
 define('INCLUDE_PATH',  ZEND_LIB_DIR1 . OS_PATH_DELI  . APP_SRC_DIR
           . OS_PATH_DELI . LOG4PHP_DIR);

 /* TODO change this to false in production, or move this to appconfig?
  * TODO move this constants to appconfig? */
 define('DISPLAY_EXCEPTION_ENABLE', false);

?>
