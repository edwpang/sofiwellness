<?php
/* $Id: home_index.php,v 1.18 2009/10/04 00:02:10 gorsen Exp $
 * Created on 2007-2-23, 23:12:14
 * @author steven
 */
require_once 'bootstrap.php';
require_once 'ZendHelper.php';

require_once APP_PLUGIN_DIR .'/AuthPlugin.php';


$controller = Zend_Controller_Front::getInstance();
$controller->throwExceptions(DISPLAY_EXCEPTION_ENABLE);
$controller ->setParam('useModules', true);
       // 'default'       =>'../Application/Default/Controllers',
if (LOG_DEBUG_FG) {
  //$debugLogger = Spike_Util_Log_Factory::getLogger(DEBUG_LOG);
  $debugLogger = ZendHelper::getLogger(LOGGER_NAME);
  $debugLogger->info('*************************************');
}
$mainAppPath = APP_ROOT .'/Application';

$controller ->setControllerDirectory(array(
        'default'       => $mainAppPath .'/Home/Controllers',
        'appointment'   => $mainAppPath .'/Appointment/Controllers',
        'auth'          => $mainAppPath .'/Auth/Controllers',
        'service'       => $mainAppPath .'/Service/Controllers',
        'myaccount'     => $mainAppPath .'/MyAccount/Controllers',
        'contact'       => $mainAppPath .'/Contact/Controllers',
        'aboutus'       => $mainAppPath .'/About/Controllers',
        'user'          => $mainAppPath .'/User/Controllers',
        'admin'         => $mainAppPath .'/Admin/Controllers',
        'sitemap'       => $mainAppPath .'/Sitemap/Controllers'
));

//for plugin
$controller->registerPlugin(new AuthPlugin());

$controller->setParam('useDefaultControllerAlways', true);
$controller->setParam('noViewRenderer', true);//temp

$controller->dispatch();
?>