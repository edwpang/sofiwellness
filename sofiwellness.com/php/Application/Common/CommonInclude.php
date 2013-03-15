<?php
/*
 * $Id:$
 * FILE:CommonInclude.php
 * CREATE: May 14, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
 
define('APP_HOME_DIR', APP_MAIN_PATH . '/Home');
define('APP_APPOINTMENT_DIR', APP_MAIN_PATH .'/Appointment');
define('APP_USERMGMT_DIR', APP_MAIN_PATH .'/UserMgmt');
define('APP_ADMIN_DIR', APP_MAIN_PATH .'/Admin');
define('APP_AUTH_DIR', APP_MAIN_PATH .'/Auth');
define('APP_MYACCOUNT_DIR', APP_MAIN_PATH .'/MyAccount');
define('APP_CONTACT_DIR', APP_MAIN_PATH .'/Contact');
define('APP_SERVICE_DIR', APP_MAIN_PATH .'/Service');
define('APP_ABOUT_DIR', APP_MAIN_PATH .'/About');
define('APP_USER_DIR', APP_MAIN_PATH .'/User');

//controllers
require_once  APP_COMMON_DIR . '/Controllers/BaseController.php';

//models
require_once  APP_COMMON_DIR .'/Models/GlobalConstants.php';
require_once  APP_COMMON_DIR .'/Models/ArrayList.php';
require_once  APP_COMMON_DIR .'/Models/Map.php';
require_once  APP_COMMON_DIR .'/Models/Item.php';
require_once  APP_COMMON_DIR .'/Models/ItemList.php';
require_once  APP_COMMON_DIR .'/Models/ItemTypes.php';
require_once  APP_COMMON_DIR .'/Models/ImageInfo.php';
require_once  APP_COMMON_DIR .'/Models/MessageInfo.php';
//Utils
require_once  APP_COMMON_DIR . '/Util/ServerParams.php';
require_once  APP_COMMON_DIR . '/Util/Utils.php';
require_once  APP_COMMON_DIR . '/Util/DatetimeUtil.php';
require_once  APP_COMMON_DIR . '/Util/Upload.php';
require_once  APP_COMMON_DIR . '/Util/FileUploadHandler.php';
require_once  APP_COMMON_DIR . '/Util/ImageUploadHandler.php';
require_once  APP_COMMON_DIR . '/Util/ImageWrapper.php';
require_once  APP_COMMON_DIR . '/Util/CanadaHolidayCalculator.php';
require_once  APP_COMMON_DIR . '/Util/HolidayNames.php';
require_once  APP_COMMON_DIR . '/Util/IniConfigUtil.php';
require_once  APP_COMMON_DIR . '/Util/ValidateUtil.php';
require_once  APP_COMMON_DIR . '/Util/EmailSender.php';

//db
require_once  APP_COMMON_DIR . '/Db/DbAccessor.php';
require_once  APP_COMMON_DIR . '/Db/BaseDbAccessor.php';
require_once  APP_COMMON_DIR . '/Db/ImageInfoDbAccessor.php';
//views
require_once  APP_COMMON_DIR . '/Views/AbstractUiComponent.php';
require_once  APP_COMMON_DIR .'/Views/AlertBox.php';
require_once  APP_COMMON_DIR .'/Views/CalendarTable.php';
require_once  APP_COMMON_DIR .'/Views/DynamicTooltip.php';
require_once  APP_COMMON_DIR .'/Views/HeadItemWriter.php';
require_once  APP_COMMON_DIR .'/Views/Toolbar.php';
require_once  APP_COMMON_DIR .'/Views/VerticalImgMenu.php';
require_once  APP_COMMON_DIR .'/Views/SsTable.php';
require_once  APP_COMMON_DIR .'/Views/MiniCalendarTable.php';
require_once  APP_COMMON_DIR .'/Views/GeneralList.php';

//for user management
require_once APP_USERMGMT_DIR .'/UserManager.php';

?>