<?php
/* $Id: cache_config.php,v 1.3 2008/04/18 19:00:13 gorsen Exp $
 * Created on 2007-3-30 1:36:43
 * @auther steven
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 define('CACHE_LOCAL_FILE_LIFETIME', 7200);
 define('CACHE_LOCAL_FILE_AUTO_SERIAL', true);
 define('CACHE_LOCAL_FILE_MASTER_FILE', APPCONFIG_INI_ABS_PATH);
 
 /* Cache dir will be moved to a 'var' directory
  * 
  */
 define ('APP_VAR_DIR', '../var'); 
  
 define('CACHE_LOCAL_FILE_DIR', APP_VAR_DIR . '/cacheDir/filecache');
 
 define('CACHE_SHORTTERM_LIFETIME', 480);
 define('CACHE_SHORTTERM_AUTO_SERIAL', true);
 define('CACHE_SHORTTERM_BACKEND_TYPE', 'File');
 define('CACHE_SHORTTERM_DIR', APP_VAR_DIR . '/cacheDir/shortterm');
 define('CACHE_MIDTERM_DIR', APP_VAR_DIR . '/cacheDir/midterm');
 
 
?>
