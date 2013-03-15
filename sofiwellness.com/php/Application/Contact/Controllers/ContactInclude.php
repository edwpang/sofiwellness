<?php
/*
 * $Id:$
 * FILE:ContactInclude.php
 * CREATE: Jun 7, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
//require_once 'ContactConstants.php';

require_once  APP_COMMON_DIR . '/CommonInclude.php';

define('CONTACT_MODULE_VIEWS',       APP_CONTACT_DIR . '/Views');
define('CONTACT_MODULE_MODELS',      APP_CONTACT_DIR . '/Models');
define('CONTACT_MODULE_CONTROLLERS', APP_CONTACT_DIR . '/Controllers');


Zend_Loader::loadClass('Zend_Controller_Action');
Zend_Loader::loadClass('Zend_View');
?>