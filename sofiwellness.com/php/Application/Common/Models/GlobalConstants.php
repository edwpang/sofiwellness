<?php
/*
 * Created on Mar 20, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class GlobalConstants
{
	//site stuffs
	const SITE_NAME = 'Sophie Wellness Centre';
	const SITE_HTTP_REF = 'http://sofiwellness.com';
	const SITE_EMAIL = 'http://sofiwellness.com';
	const SITE_TITLE = 'Sophie Wellness Centre';
	
	//phone fax
	const CONTACT_PHONE = '905-886-8399';
	const CONTACT_FAX = '866-486-6558';
	
	//main app mode
	const APP_MODE = 'app_mode';
	const APPMODE_SPORTS = 'sports';
	const APP_SPORTS = true;
	
	//common keys or names
	const TOTAL = 'total';
	const MORE = 'more';
	const USER_NAME = 'userName';
	const USER_ID = 'userId';
	const ACCOUNT_TYPE = 'account_type';
	const USER_INFO = 'userInfo';
	const CATEGORY = 'category';
	const CATEGORY_ID = 'category_id';
	const TYPE = 'type';
	const ERROR_MESSAGE = 'error_message';
	const INFO_MESSAGE = 'info_message';
	const SHOW_MESSAGE = 'show_message';
	const CAPTCHA = 'captcha';
	
	const RETURN_URL = 'return_url';
	const FORM_ACTION = 'form_action';
	const FORM  = "form";
	const JUST_SIGNUP = 'just_signup';
	
	//session namespace
	const SESSION_DEF = 'sesion_def';
	const SESSION_HOME = 'session_home';
	const SESSION_MB = 'session_mb';
	
	//main tab name
	const HOME = 'Home';
	const SERVICE = 'Services&Fee';
	const ABOUT_US = 'About Us';
	const APPOINTMENT = 'Appointment';
	const CONTACT_US = 'Contact Us';
	const MY_ACCOUNT = 'My Account';
	const ADMIN = 'Admin';
	const USER = 'User';
		
	//main tab id
	const TABID_HOME = 'home';
	const TABID_SERVICE = 'service';
	const TABID_ABOUT_US = 'aboutus';
	const TABID_APPOINTMENT = 'appointment';
	const TABID_CONTACT_US = 'contact';
	const TABID_ADMIN = 'admin';
	const TABID_MY_ACCOUNT = 'myaccount';
	const TABID_USER = 'user';
 
    //language
    const CHINESE = 'zh';
    const ENGLISH = 'en';
	
	//some setting
	const DEF_PAGE_SIZE = 20;
	const AVATAR_IMG_WIDTH = 80;
	const AVATAR_IMG_HEIGHT = 80;
	

	//for seperator
	const SEPERATOR = 'menu_seperator';
	const TAG_REGEXP = "[ ,]+";
	
	
	//some important values
	const PWD_SALT = 'one world one dream';
		
	
	public static function getAppMode ()
	{
		return GlobalConstants::APP_SPORTS;	
	}
	
	public static function getMainCssFile ()
	{
		if (RUN_MODE == 'prod')
			$def_stylesheet = CSS_FOLDER .'/' .GlobalConstants::MAIN_CSS_DEFAULT_RT;	
		else
			$def_stylesheet = CSS_FOLDER .'/' . GlobalConstants::MAIN_CSS_DEFAULT;
			
		return $def_stylesheet;
	}
	
	public static function getJsDir ()
	{
		return JS_FOLDER;	
	}
	
	public static function getImageSysDir()
	{
		return IMAGE_SYS_FOLDER;	
	}
	
	public static function getLogoImage ()
	{
		//return IMAGE_SYS_FOLDER .'/logo_opt64.gif';	
		//return IMAGE_SYS_FOLDER .'/greenhill.gif';	
		//return CSS_FOLDER .'/images/greenhill.gif';
		return IMAGE_SYS_FOLDER .'/header.jpg';//'/logo.png';;
	}
	
	public static function getImageAvatarDir ()
	{
		return IMAGE_AVATAR_FOLDER;	
	}
	
	
	public static function getTmpUploadDir ()
	{
		return TMP_IMPORT_FOLDER;	
	}
	
	public static function getTmpFileDir ()
	{
		return TMP_IMPORT_FOLDER;	
	}
	
	public static function getJsFile ($jsFileName)
	{
		return JS_FOLDER .'/' .$jsFileName;	
	}
	
	public static function getCssFile ($jsFileName)
	{
		return CSS_FOLDER .'/' .$jsFileName;	
	}
	
	
	public static function getSysImage ($imageName)
	{
		return IMAGE_SYS_FOLDER .'/' .$imageName;	
	}
	
	
	public static function getCssImage ($imageName)
	{
		return CSS_IMAGE_FOLDER .'/' .$imageName;	
	}
	
	
	public static function getAvatarImage ($imageName)
	{
		return GlobalConstants::getImageAvatarDir () .'/' .$imageName;	
	}
	
	public static function getSourceDataRealPath()
	{
		return SOURCE_DATA;
	}
	
	public static function getUserDataRealPath ()
	{
		return USER_DATA;	
	}
	
}
 
?>