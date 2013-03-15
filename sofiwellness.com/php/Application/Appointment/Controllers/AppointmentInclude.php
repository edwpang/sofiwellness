<?php
/*
 * $Id: ReminderInclude.php,v 1.2 2009/03/22 04:03:19 steven Exp $
 * FILE:ReminderInclude.php
 * CREATE: Feb 17, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once  APP_COMMON_DIR . '/CommonInclude.php';

define('APPOINTMENT_MODULE_VIEWS',       APP_APPOINTMENT_DIR . '/Views');
define('APPOINTMENT_MODULE_MODELS',      APP_APPOINTMENT_DIR . '/Models');
define('APPOINTMENT_MODULE_CONTROLLERS', APP_APPOINTMENT_DIR . '/Controllers');

require_once 'AppointmentConstants.php';
require_once APPOINTMENT_MODULE_MODELS .'/AppointmentInfo.php';
require_once APPOINTMENT_MODULE_MODELS .'/AppointmentInfoDbAccessor.php';
require_once APPOINTMENT_MODULE_MODELS .'/AppointmentForm.php';
require_once APPOINTMENT_MODULE_MODELS .'/ServiceTimeFormItem.php';
require_once APPOINTMENT_MODULE_MODELS .'/ServiceTimeForm.php';
require_once APPOINTMENT_MODULE_MODELS .'/ServiceTimeInfo.php';
require_once APPOINTMENT_MODULE_MODELS .'/ServiceTimeDbAccessor.php';


require_once APPOINTMENT_MODULE_VIEWS .'/ScheduleWriter.php';
require_once APPOINTMENT_MODULE_VIEWS .'/AppointmentListTableMaker.php';
require_once APPOINTMENT_MODULE_VIEWS .'/ServiceTimeWriter.php';


require_once APP_APPOINTMENT_DIR .'/AppointmentHelper.php';
require_once APP_APPOINTMENT_DIR .'/ServiceTimeHelper.php';

Zend_Loader::loadClass('Zend_Controller_Action');
Zend_Loader::loadClass('Zend_View');
?>