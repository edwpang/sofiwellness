<?php
/*
 * Created on Apr 25, 2007
 *
 */

require_once  APP_COMMON_DIR . '/Views/MenuBar.php';
//require_once  APP_COMMON_DIR . '/UI/DropdownMenu.php';
require_once  APP_COMMON_DIR . '/Views/MainMenuBar.php';
require_once  APP_COMMON_DIR . '/Views/TabMenu.php';
require_once APP_COMMON_DIR .'/Models/GlobalConstants.php';
require_once APP_COMMON_DIR .'/Util/ServerParams.php';


$mainPath = 'http://'.$_SERVER['SERVER_NAME'];
$mainPath = '';
$menuItems = array(
	GlobalConstants::HOME => $mainPath.'/'.GlobalConstants::TABID_HOME,
	GlobalConstants::SERVICE =>$mainPath.'/' .GlobalConstants::TABID_SERVICE,
	GlobalConstants::ABOUT_US =>$mainPath.'/' .GlobalConstants::TABID_ABOUT_US,
	GlobalConstants::APPOINTMENT =>$mainPath.'/' .GlobalConstants::TABID_APPOINTMENT,
	GlobalConstants::CONTACT_US =>$mainPath.'/' .GlobalConstants::TABID_CONTACT_US,
	GlobalConstants::MY_ACCOUNT => $mainPath .'/' .GlobalConstants::TABID_MY_ACCOUNT
);


$curMenu = ServerParams::getMainMenubarActiveMenu ();

if (!isset($curMenu))
{
	$curMenu = GlobalConstants::TABID_HOME;
}

$userId = Utils::getUserId ();
//make item list
$mainMenuList = new ItemList ();
$len = strlen ($mainPath.'/');
while(list($key, $val) = each($menuItems))
{
	if ($userId == null && $key == GlobalConstants::MY_ACCOUNT)
		continue;
		
	$item = new Item ();
	$item->setName ($key);
	$item->setHref ($val);
	$id = substr ($val, $len);
	if ($id == $curMenu)
		$item->setSelected (true);

	$mainMenuList->add ($item);
}

if (UserManager::isAdminOrKeeper())
{
	$item = new Item ();
	$item->setName (GlobalConstants::USER);
	$href = $mainPath .'/' .GlobalConstants::TABID_USER;
	$item->setHref ($href);
	$id = substr ($href, $len);
	if ($id == $curMenu)
		$item->setSelected (true);
	$mainMenuList->add ($item);		
}

if (UserManager::isAdmin())
{
	$item = new Item ();
	$item->setName (GlobalConstants::ADMIN);
	$href = $mainPath .'/' .GlobalConstants::TABID_ADMIN;
	$item->setHref ($href);
	$id = substr ($href, $len);
	if ($id == $curMenu)
		$item->setSelected (true);
	$mainMenuList->add ($item);	
}

//$tm = new MainMenuBar ();
$tm = new TabMenu();
if ($this->headerStyle != null)
	$tm->setBackgroundColor ($bkColor);
$tm->setItems ($mainMenuList);
$tm->output ();


?>