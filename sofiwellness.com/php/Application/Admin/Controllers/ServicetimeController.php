<?php
/*
 * $Id:$
 * FILE:ServicetimeController.php
 * CREATE: Jun 2, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AdminInclude.php'; 
require_once APP_APPOINTMENT_DIR .'/Controllers/AppointmentInclude.php';
 
class Admin_ServicetimeController extends BaseController
{
	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(ADMIN_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_NAME;
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_ADMIN);
         $this->view->view_mode = 'admin';
	}	

    public function indexAction()
    {
    	Log::debug ('ServicetimeController::indexAction');
    	
    	if (!UserManager::isAdmin ())
    	{
    		//to login
    		parent::setLastUrl ('/admin');
    		$this->_redirect('/auth/user/login');	
    		return;
    	}
    	
    	
    	$info = new ServiceTimeInfo ();
    	$timeList = AppointmentHelper::getDefBizTimeList();
    	$timeInfo = ServiceTimeHelper::getGeneralBusinessHour();
    	$satInfo = ServiceTimeHelper::getGeneralSaturdayBusinessHour();
    	if ($satInfo == null)
    	{
    		$satInfo = new ServiceTimeInfo ();
    	}
    	$sunInfo = ServiceTimeHelper::getGeneralSundayBusinessHour();
    	if ($sunInfo == null)
    		$sunInfo = new ServiceTimeInfo ();
    	$endTime = $timeList->get ($timeList->size()-1);
    	
    	$this->view->info = $timeInfo;
    	$this->view->info_sun = $sunInfo;
    	$this->view->info_sat = $satInfo;
    	$this->view->time_list = $timeList;
    	
    	$this->view->list_off_day = ServiceTimeHelper::getGeneralWeekOffDates();
    	$this->view->list_holiday = ServiceTimeHelper::getGeneralOffHolidays ();
    	
    	parent::setLeftPanel (null, '8%');
    	parent::setRightPanel ('service_datetime_form.php', '90%');
		echo $this->view->render('AdminView.php');
    }
    
    public function saveAction ()
    {
    	Log::debug ('servicetimeController::save');
    	$form = Utils::getSimpleFormData();
    	$infoHour = $this->formToServiceHour ($form, null);
    	$infoHourSat = $this->formToServiceHour ($form, 'sat');
   		$infoHourSun = $this->formToServiceHour ($form, 'sun');
    	
    	$listWeek = $this->formToWeek($form);
    	$listHoliday = $this->formToHoliday ($form);
    	$userId = 0;
    	$db = new ServiceTimeDbAccessor();
    	
    	Log::debug ('save business hour setting');
     	$list = $db->getServiceTimeInfoListByType ($userId, ServiceTimeInfo::BUSINESS_HOUR);
    	if ($list != null && $list->size() > 0)
    	{
    		$oInfo = $list->get(0);
    		$infoHour->setId($oInfo->getId());
    		$db->updateServiceTimeInfo ($infoHour);
    	}
    	else
    		$db->saveServiceTimeInfo ($infoHour);
    	
    	//save saturday
     	$list = $db->getServiceTimeInfoListByType ($userId, ServiceTimeInfo::BUSINESS_HOUR_SAT);
    	if ($list != null && $list->size() > 0)
    	{
    		$oInfo = $list->get(0);
    		$infoHourSat->setId($oInfo->getId());   	
    		$db->updateServiceTimeInfo ($infoHourSat);
    	}
    	else
    		$db->saveServiceTimeInfo ($infoHourSat);

    	//save sunday
     	$list = $db->getServiceTimeInfoListByType ($userId, ServiceTimeInfo::BUSINESS_HOUR_SUN);
    	if ($list != null && $list->size() > 0)
    	{
    		$oInfo = $list->get(0);
    		$infoHourSun->setId($oInfo->getId());   	
    		$db->updateServiceTimeInfo ($infoHourSun);
    	}
    	else
    		$db->saveServiceTimeInfo ($infoHourSun);
    	
    	
    	//for week days
    	Log::debug ('save week day setting');
    	$oListWeek = $db->getServiceTimeInfoListByType ($userId, ServiceTimeInfo::BUSINESS_DAY);
    	$this->saveServiceTimeList ($listWeek, $oListWeek, $db);
   	
    	//for holidays
    	Log::debug ('save holiday setting');
    	$oListHoliday = $db->getServiceTimeInfoListByType ($userId, ServiceTimeInfo::HOLIDAY);
    	$this->saveServiceTimeList ($listHoliday, $oListHoliday, $db);
    	
    	
    	$this->_redirect('/admin/servicetime');
    		
    }
    
    
    /////////////////////////////////////////////
    //private
    private function formToServiceHour ($form, $whichday=null)
    {
    	//for start time and end time
     	$tInfo = new ServiceTimeInfo();
     	$start = $form->get('start_time');
     	if ($whichday == 'sat')
     		$start = $form->get('start_time_sat');
     	else if ($whichday == 'sun')
     		$start = $form->get ('start_time_sun');
     		
     	$startTime = date('H:i:s' ,strtotime($start));
    	$tInfo->setStartTime ($startTime);
     	$end = $form->get('end_time');
     	if ($whichday == 'sat')
     		$end = $form->get('end_time_sat');
     	else if ($whichday == 'sun')
     		$end = $form->get ('end_time_sun');     	
     	
     	$endTime = date('H:i:s' ,strtotime($end));
    	$tInfo->setEndTime ($endTime);
    	$tInfo->setOnDuty (ServiceTimeInfo::ON);
    	$tInfo->setTypeName (serviceTimeInfo::BUSINESS_HOUR);
    	if ($whichday == 'sat')
    		$tInfo->setTypeName (serviceTimeInfo::BUSINESS_HOUR_SAT);
     	else if ($whichday == 'sun')
    		$tInfo->setTypeName (serviceTimeInfo::BUSINESS_HOUR_SUN);
    	
    	$tInfo->setUserId (0);
    	return $tInfo; 
    }
    
    private function formToWeek($form)
    {
    	$list = new ArrayList ();
   		$dates = DatetimeUtil::getWeekDayNames();
    	foreach ($dates as $key => $date)
		{
			$date = strtolower ($date);
			$day = $form->get($date);
			if ($day != null)
			{
				Log::debug ('week day off:' .$day);
				$info = new ServiceTimeInfo ();
				$info->setTheDate ($day);
				$info->setTypeName (ServiceTimeInfo::BUSINESS_DAY);
				$info->setOnDuty (ServiceTimeInfo::OFF);
				$list->add ($info);
			}
		}
	    
	    return $list;
    }
    
    private function formToHoliday ($form)
    {
    	$list = new ArrayList();
 		$names = CanadaHolidayCalculator::getHolidayNames();
		foreach ($names  as $name)
		{
			Log::debug ('name:' .$name);
			$sId = strtolower ($name);
			$sId = str_replace (" ", "_", $sId);
			$day = $form->get($sId);	
			if ($day != null)
			{
				Log::debug ('day off:' .$day);
				$info = new ServiceTimeInfo ();
				$info->setTheDate ($name);
				$info->setTypeName (ServiceTimeInfo::HOLIDAY);
				$info->setOnDuty (false);
				$list->add ($info);					
			}
		}
				
		return $list;   	
    }
    
    
    private function formToServiceTimeIntoList ($form)
    {
    	$userId = 0; //0 for all 
    	$list = new ArrayList ();
    	
    	//for start time and end time
     	$tInfo = new ServiceTimeInfo();
    	$tInfo->setStartTime ($form->get('start_time'));
    	$tInfo->setEndTime ($form->get('end_time'));
    	$tInfo->setOnDuty (true);
    	$tInfo->setTypeName (serviceTimeInfo::BUSINESS_HOUR);
    	$tInfo->setUserId ($userId); 
    	$list->add ($tInfo);
    	
    	//for week and weeken
    	$dates = DatetimeUtil::getWeekDayNames();
    	$list = new ArrayList ();
    	foreach ($dates as $key => $date)
		{
			$date = strtolower ($date);
			$day = $form->get($date);
			if ($day != null)
			{
				Log::debug ('week day off:' .$day);
				$info = new ServiceTimeInfo ();
				$info->setTheDate ($day);
				$info->setTypeName (ServiceTimeInfo::BUSINESS_DAY);
				$info->setFlag (false);
				$list->add ($info);
			}
		}
		
		//for holiday
		$names = CanadaHolidayCalculator::getHolidayNames();
		foreach ($names  as $name)
		{
			Log::debug ('name:' .$name);
			$sId = strtolower ($name);
			$sId = str_replace (" ", "_", $sId);
			$day = $form->get($sId);	
			if ($day != null)
			{
				Log::debug ('day off:' .$day);
				$info = new ServiceTimeInfo ();
				$info->setTheDate ($name);
				$info->setTypeName (ServiceTimeInfo::HOLIDAY);
				$info->setFlag (false);
				$list->add ($info);					
			}
		}
				
		return $list;
    }
    
    
    private function saveServiceTimeList ($list, $oList, $db)
    {
    	$userId = 0;
    	$count = $list->size();
    	for ($i = 0; $i < $count; $i++)
    	{
    		$info = $list->get ($i);
    		$info->setUserId ($userId);
    		$oInfo = null;
    		if ($oList != null && $oList->size () > 0)
    		{
    			$oCount = $oList->size();
    			for ($i = 0; $i < $oCount; $i++)	
    			{
    				$item = $oList->get($i);
    				if ($item->getTheDate() == $info->getTheDate())
    				{
    					$oInfo = $item;
    					break;
    				}
    			}
    		}
    		if ($oInfo == null)
    			$db->saveServiceTimeInfo($info);
    	}
    	
    	$count = $oList->size();
    	for ($i = 0; $i < $count; $i++)
    	{
    		$info = $oList->get ($i);
    		$oInfo = null;
    		if ($list != null)
    		{
    			$oCount = $list->size();
    			for ($i = 0; $i < $oCount; $i++)	
    			{
    				$item = $list->get($i);
    				if ($item->getTheDate() == $info->getTheDate())
    				{
    					$oInfo = $item;
    					break;
    				}
    			}
    		}
    		if ($oInfo == null)
    			$db->deleteeServiceTimeInfo($info->getId());
    	}   	
    }
}
?>