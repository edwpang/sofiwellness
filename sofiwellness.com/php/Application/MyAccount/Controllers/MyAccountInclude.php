<?php
/*
 * $Id:$
 * FILE:MyAccountInclude.php
 * CREATE: May 28, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once  APP_COMMON_DIR . '/CommonInclude.php';

define('MYACCOUNT_MODULE_VIEWS',       APP_MYACCOUNT_DIR . '/Views');
define('MYACCOUNT_MODULE_MODELS',      APP_MYACCOUNT_DIR . '/Models');
define('MYACCOUNT_MODULE_CONTROLLERS', APP_MYACCOUNT_DIR . '/Controllers');

//require_once 'AppointmentConstants.php';
//require_once MYACCOUNT_MODULE_MODELS .'/AppointmentInfo.php';
//require_once MYACCOUNT_MODULE_MODELS .'/AppointmentInfoDbAccessor.php';
//require_once MYACCOUNT_MODULE_MODELS .'/AppointmentForm.php';


//require_once MYACCOUNT_MODULE_VIEWS .'/ScheduleWriter.php';
//require_once MYACCOUNT_MODULE_VIEWS .'/AppointmentListTableMaker.php';


//require_once APP_MYACCOUNT_DIR .'/AppointmentHelper.php';

Zend_Loader::loadClass('Zend_Controller_Action');
Zend_Loader::loadClass('Zend_View');
?>