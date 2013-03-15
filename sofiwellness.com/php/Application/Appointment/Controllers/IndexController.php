<?php
/*
 * $Id: IndexController.php,v 1.1 2009/02/26 19:55:58 gorsen Exp $
 * FILE:IndexController.php
 * CREATE: Feb 17, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AppointmentInclude.php';


class Appointment_IndexController extends BaseController
{
	private $_pageSize = GlobalConstants::DEF_PAGE_SIZE;

	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(APPOINTMENT_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_TITLE .':Appointments';
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_APPOINTMENT);
	}

    public function indexAction()
    {
    	Log::debug ('###AppointmentIndex::indexAction');
    	$params = ServerParams::getQueryParameters();
    	$month = $params['m'];
    	$year = $params['y'];
    	
    	//Log::debug('year:' .$year .', month:' .$month);
    	if ($year == null || $month == null)
    	{
    		$dateTime = DatetimeUtil::getCurrentGMDateTime();	
    		$year = $dateTime->format('Y');
    		$month = $dateTime->format('n');
    	}
    	
    
		$userId = Utils::getUserId();
		$isJustSignup = (SessionUtil::get (GlobalConstants::JUST_SIGNUP) != null) ? true:false;
    	if (AccountTypes::isCustomerAccount(Utils::getAccountType()))
        {
        	//add userId to appointment those does not have user id
        	$userInfo = Utils::getUserInfo();
        	if ($userInfo != null)
        	{
        		$fName = $userInfo->getFirstName();
        		$lName = $userInfo->getLastName();
        		$list = AppointmentHelper::getAppointmentListWithoutUserId ($fName, $lName);
        		if ($list != null && $list->size () > 0)
        		{
        			$db = new AppointmentInfoDbAccessor();
        			for ($i = 0; $i < $list->size(); $i++)
        			{
        				$info = $list->get($i);
        				$info->setUserId ($userId);	
        				$db->updateAppointmentInfo ($info);
        			}
        		}
        	}
        	if ($isJustSignup)
        		SessionUtil::remove (GlobalConstants::JUST_SIGNUP);
        }
        

		parent::setLastUrl ('/appointment');
    	//$itemList = $this->getAppointmentList ($userId, $year, $month);
    	//Log::debug ('appointment itemlist size:' .$itemList->size());
    	$this->view->year = $year;
    	$this->view->month =$month;
    	//$this->view->sourceList = $itemList;
    	$this->view->lock_callback_func = array($this, 'isDateLockCB');
    	
    	echo $this->view->render('AppointmentView.php');
    }
    
    
    public function isDateLockCB ($date)
    {
    	return ServiceTimeHelper::isOffDay($date);
    	
    }
    
    private function getAppointmentList ($userId, $year, $month, $isWho=false)
    {
    	$itemList = new ItemList();
    	$helper = new AppointmentHelper();
    	$list = $helper->getAppointmentListForTheMonth ($userId, $year, $month);
    	$count = $list->size();
    	for ($i = 0; $i < $count; $i++)
    	{
    		$info = $list->get($i);
    		$item = new Item();
    		AppointmentHelper::appointmentInfoToItem ($info, $item);
    		$itemList->add ($item);
    	}
    	
    	return $itemList;
    }
    
}
?>