<?php
/* $Id: bootstrap.php,v 1.3 2008/11/18 19:55:03 gorsen Exp $
 * Created on 2007-2-24 2:16:21
 * @author steven
 *
 * This bootstrap file should be require_once in every *index.php
 */
require_once 'constants.php';
require_once 'application_constants.php';

//set_include_path(INCLUDE_PATH);
ini_set('include_path', INCLUDE_PATH);

/* Loading Zend Classes */
/*include 'Zend.php'; */
require_once 'Zend/Session.php';
require_once 'Zend/Config/Ini.php';
require_once 'Zend/Db.php';

require_once 'Zend/Log.php';                  // Zend_Log base class
require_once 'Zend/Log/Writer/Stream.php';

//require_once 'Spike/Controller/SpikeBaseAction.php';
//require_once 'Zend/Filter/Input.php';

/* Loading 3rd party Classes */
require_once('LoggerManager.php');
/* loading application files */
//require_once 'spike_common_appconfig_funcs.php';
//require_once 'spike_common_zend_cache_funcs.php';

require_once 'ZendHelper.php';
/* Loading Application Classes*/

//Zend_Loader::loadClass('Spike_Util_Auth');
Zend_Loader::loadClass('Zend_Controller_Front');
Zend_Loader::loadClass('Zend_Registry');
Zend_loader::loadClass ('ZendHelper');


//to avoid page expire problem when click back button
//ini_set('session.cache_limiter', 'private');

//Spike_Util_ZendHelper::startSession();
ZendHelper::startSession();
?>
