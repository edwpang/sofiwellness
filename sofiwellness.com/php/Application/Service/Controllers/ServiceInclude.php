<?php
/*
 * $Id:$
 * FILE:ServiceInclude.php
 * CREATE: Jun 18, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'ServiceConstants.php';

require_once  APP_COMMON_DIR . '/CommonInclude.php';

require_once SERVICE_MODULE_MODELS .'/ServiceItemInfo.php';
require_once SERVICE_MODULE_MODELS .'/ServiceItemDbAccessor.php';




Zend_Loader::loadClass('Zend_Controller_Action');
Zend_Loader::loadClass('Zend_View');
?>