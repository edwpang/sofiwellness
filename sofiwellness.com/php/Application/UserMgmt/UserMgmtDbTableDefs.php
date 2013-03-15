<?php
/*
 * Created on Sep 14, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
//define('TABLE_USER_INFO', 'users_table');

//define('USER_ID', 'user_id');
//define('USER_NAME', 'user_name');
 
class UserMgmtDbTableDefs
{
	//login table
	const TABLE_LOGIN = 'users_login';
	
	const USER_ID = 'user_id';
	const USER_NAME = 'user_name';
	const PASSWORD= 'user_password';
	const ACCOUNT_TYPE = 'account_type';
	const QUESTION = 'question';
	const ANSWER = 'answer';
	const CREATED_TIME = 'create_time';
	const LAST_VISIT_TIME = 'last_visit';
	const LAST_LOGOUT_TIME = 'last_logout';
	
	//users_profile table
	const TABLE_PROFILE = 'users_profile';
	
	const ID = 'id';
	const FIRST_NAME = 'first_name';
	const LAST_NAME = 'last_name';
	const PRIMARY_EMAIL = 'primary_email';
	const TIME_ZONE = 'time_zone';
	const COUNTRY = 'country';
	const STATE = 'state';
	const CITY = 'city';
	const LANGUAGE = 'language';
	const IMAGE_FILE = 'image_file';
	const UPDATE_TIME = 'update_time';

	//users_openid table
	const TABLE_OPENID = 'users_openid';
	const OPENID_URL = 'openid_url';
	


	//common
	const STATUS_CODE = 'status_code';
	
	/*
	const UNREG_TRACKID = 'unregistered_trackId';
	const REG_TIME = 'registered_time';
	const DEACTIVATED_TIME = ' deactivated_on';
	const IS_OBSOLETE = 'is_obsolete';
	*/
}
 
?>
