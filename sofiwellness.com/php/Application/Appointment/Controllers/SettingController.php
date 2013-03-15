<?php
/*
 * $Id:$
 * FILE:SettingController.php
 * CREATE: May 30, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AppointmentInclude.php';


class Appointment_SettingController extends BaseController
{
	

	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(APPOINTMENT_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_TITLE .':Appointment';
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_APPOINTMENT);
	}

    public function indexAction()
    {
    	Log::debug ('SettingController::indexAction');
    	$params = ServerParams::getQueryParameters();
    	$curUserId = $params['id'];
    	$this->view->error_message = parent::getErrorMessage();
    	$userId = Utils::getUserId ();
     	$theUserId = $userId;
     	if ($curUserId != null)
     		$theUserId = $curUserId;
     		
    	$serviceTimeForm = $this->getServiceTimeForm($theUserId);
    	
    	$servicetimeInfo = ServiceTimeHelper::getGeneralBusinessHour ();
   		if ($servicetimeInfo != null)
   		{
   			$start = $servicetimeInfo->getStartTimeStr();
   			$end = $servicetimeInfo->getEndTimeStr();
   			Log::debug ('weekday:start:'.$start .', end:' .$end);
   			$this->view->time_list = AppointmentHelper::getDefBizTimeList($start, $end);
   		}
   		else
    		$this->view->time_list = AppointmentHelper::getDefBizTimeList();
    	
    	if (!ServiceTimeHelper::isSaturdayOff ())
    	{
    		$satTimeInfo = ServiceTimeHelper::getGeneralSaturdayBusinessHour ();
    		if ($satTimeInfo != null)
    		{
	    		$start = $satTimeInfo->getStartTimeStr();
	    		$end = $satTimeInfo->getEndTimeStr();
	    		Log::debug ('saturday:start:'.$start .', end:' .$end);
	    		$this->view->time_list_sat = AppointmentHelper::getDefBizTimeList($start, $end);
	    		$count = $this->view->time_list_sat->size();
	    		Log::debug ('timelist:end:' .$this->view->time_list_sat->get($count-1));
    		}
    		else
    		    $this->view->time_list_sat = AppointmentHelper::getDefBizTimeList();
    	}
    	
    	if (!ServiceTimeHelper::isSundayOff ())
    	{
    		$timeInfo = ServiceTimeHelper::getGeneralSundayBusinessHour ();
    		if ($timeInfo != null)
    		{
	    		$start = $timeInfo->getStartTimeStr();
	    		$end = $timeInfo->getEndTimeStr();
	    		$this->view->time_list_sun = AppointmentHelper::getDefBizTimeList($start, $end);
    		}
    		else
    		    $this->view->time_list_sun = AppointmentHelper::getDefBizTimeList();    		
    	}
    	
    	$this->view->weekday_off_names = $this->getGeneralWeekOffDateNames();
    	
    	$defEnd = $this->view->time_list->size () - 1;
     	$this->view->service_time_form = $serviceTimeForm;
     	$this->view->current_user = Utils::getUserName();
     	$this->view->current_user_id = $theUserId;
     	
     	$aType = Utils::getAccountType();
     	if (Utils::getUserId () != null && 
     		(AccountTypes::isAdmin ($aType) || AccountTypes::isBookKeeper($aType)) )
     	{
     		//get resource list
     		$url = '/appointment/setting';
     		$listRes = $this->getResourceItemList ($url, $theUserId);
     		$this->view->resource_list = $listRes;
     		if ($curUserId != 0)
     			$this->view->current_user = $listRes->getProperty ('select_name');
     	}
     	
    	parent::setRightPanel('appointment_setting_form.php');
    	echo $this->view->render('AppointmentSettingView.php');
    }
    
    //save the service time general setting
    public function saveAction ()
    {
    	//Log::debug ('settingController::saveAction');
    	
    	$form = Utils::getSimpleFormData();
    	$userId = $form->get('user_id');
    	if ($userId == null)
    		$userId = Utils::getUserId ();
    	//Log::debug ('userId=' .$userId);
    	$err = null;
    	$list = $this->formToServiceTimeInfoList ($form);
    	//Log::debug ('lsi=' .$list->size());
    	if ($list != null && $list->size() > 0)
    	{
    		$updateTime = DatetimeUtil::getCurrentTimeStr();
    		$db = new ServiceTimeDbAccessor();
    		$count = $list->size();
    		for ($i = 0; $i < $count; $i++)
    		{
    			$info = $list->get($i);
    			$info->setUserId ($userId);
    			$info->setUpdateTime ($updateTime);
    			$theDate = $info->getTheDate ();
    			$oInfo = $db->getServiceTimeInfoByDateType ($userId, $theDate, 1, ServiceTimeInfo::GENERAL);
    			if ($oInfo != null)
    			{
    				$info->setId ($oInfo->getId ());
    				$db->updateServiceTimeInfo ($info);
    			}
    			else
    			{
    				$db->saveServiceTimeInfo($info);
    			}
    		}
    		$err = 'INFO:The form has been saved.';
    	}

		if ($err != null)
    		parent::setErrorMessage ($err);
    	parent::setForm ($form);
    	$this->_redirect('/appointment/setting');
    }
    
    public function dayAction ()
    {
    	Log::debug ('settingController::dayAction');
    	$this->view->error_message = parent::getErrorMessage(true);
    	$formItem = parent::getForm ();
    	if ($formItem == null)
    	{
    		$formItem = new ServiceTimeFormItem ();
    		$formItem->setOn (false);
    	}
    	$curUserId = ServerParams::getQueryParameter ('id');
    	$userId = Utils::getUserId();
    	$theUserId = $userId;
    	if ($curUserId != null)
    	{
    		$theUserId = $curUserId;
     	}
    	$info = UserManager::getUserInfoById ($theUserId);
    	$userName = $info->getDisplayName();
    		
     	$formItem->setId ($theUserId);
     	$this->view->current_user = $userName;
    	$this->view->timeList = AppointmentHelper::getDefBizTimeList();
     	$this->view->form = $formItem;
     	
    	parent::setRightPanel('appointment_setting_date_panel.php');
    	echo $this->view->render('AppointmentSettingView.php');
    }
    
    public function savedateAction ()
    {
    	$err = null;
    	$form = Utils::getSimpleFormData();
    	$userId = $form->get('user_id');
    	if ($userId == null)
    		$userId = Utils::getUserId();
    	$item = $this->formToFormItem ($form);
    	if ($form->get('service_date') == null)
    	{
    		$err = 'You must enter the day';		
    	}
    	else
    	{
    		//save	
    		$info = $item->toInfo ();
    		$info->setUserId ($userId);
    		$info->setTypeName (ServiceTimeInfo::DAY_OFF);
    		$info->setUpdateTime (DatetimeUtil::getCurrentTimeStr());
    		Log::debug ('userId:' .$info->getUserId ());
    		$db = new ServiceTimeDbAccessor();
    		//check
    		if ($info->getId () != null)
    			$db->updateServiceTimeInfo($info);
    		else
    			$db->saveServiceTimeInfo ($info);
    		$err = 'Your service time has been saved.';
    	}		
    	
    	parent::setErrorMessage ($err);
    	parent::setForm ($item);
    	$this->_redirect('/appointment/setting/day');
    }
    
    ///////////////////////////////////
    //private
    private function getServiceTimeForm($resourceId)
    {
    	$form = new ServiceTimeForm();
    	$form->setUserId ($resourceId);
    	$db = new ServiceTimeDbAccessor ();
    	$list = $db->getServiceTimeInfoListBy ($resourceId, null, 1, ServiceTimeInfo::GENERAL);
    
    	if ($list != null && $list->size() > 0)
    	{
    		$count = $list->size();
    		for ($i = 0; $i < $count; $i++)
    		{
    			$info = $list->get($i);
    			$item = AppointmentHelper::serviceTimeInfoToServiceTimeFormItem ($info);
    			$form->addItem ($item);
    		}
    	}	
    	return $form;
    }
    
    private function formToServiceTimeInfoList ($form)
    {
    	$dates = DatetimeUtil::getWeekDayNames();
    	$list = new ArrayList ();
    	foreach ($dates as $key => $date)
		{
			$date = strtolower ($date);
			$day = $form->get($date);
			if ($day != null)
			{
				$info = new ServiceTimeInfo ();
				$info->setUserId($form->get('user_id'));
				$info->setTheDate ($day);
				$start = $form->get($date .'_start_time');
				$start = DatetimeUtil::formatDatetimeStr ($start, DatetimeUtil::SHORT_TIME_PATTEN);
				$info->setStartTime ($start);
				$end = $form->get($date .'_end_time');
				//should  change to H:i
				$end = DatetimeUtil::formatDatetimeStr ($end, DatetimeUtil::SHORT_TIME_PATTEN);
				$info->setEndTime ($end);
				$info->setTypeName (ServiceTimeInfo::GENERAL);
				$info->setOnDuty (true);
				$list->add ($info);
			}
		}
				
		return $list;
    }
    
    private function formToFormItem ($form)
    {
    	$item = new ServiceTimeFormItem();
    	$item->setDate ($form->get('service_date'));
    	$off = $form->get('date_off');
    	if ($off != null)
    		$item->setOn (false);
    	else
    		$item->setOn (true);
    	$item->setStartTime($form->get('start_time'));
    	$item->setEndTime ($form->get('end_time'));
    	$item->setNote ($form->get('note'));
    	
    	return $item;
    }
    
    private function getGeneralWeekOffDateNames ()
    {
    	$list = ServiceTimeHelper::getGeneralWeekOffDates ();	
    	if ($list == null || $list->size() == 0)
    		return null;
    	$names = new ArrayList ();
    	$count = $list->size();
    	for ($i = 0; $i < $count; $i++)
    	{
    		$info = $list->get($i);	
    		$date = $info->getTheDate();
    		$names->add ($date);
    	}
    	
    	return $names;
    }
    
	private function getResourceItemList ($url, $selectedId)
 	{
 		$list = UserManager::getUserInfoListByType (AccountTypes::PROFESSIONAL);
 		
 		//now get user info and image info and convert to item
 		$imageDb = new ImageInfoDbAccessor();
 		$itemList = new ItemList ();
 		$count = $list->size();
 		for ($i = 0; $i < $count; $i++)
 		{
  			$userInfo = $list->get($i);
 			$imageList = $imageDb->getImageByUserIdType ($userInfo->getId(),null);
			if ($imageList != null && $imageList->size() > 0)
				$userInfo->setImageInfo ($imageList->get(0));
			$item = new Item();
			$name = $userInfo->getDisplayName();
			$title = $userInfo->getTitle();
			if ($title != null)
				$name = $title .' '. $userInfo->getFirstName();
			$item->setName ($name);
			$item->setId ($userInfo->getId());
			$href = $url .'?id='.$userInfo->getId();
			$item->setHref ($href);
			if ($selectedId != 0 && $selectedId == $userInfo->getId ())
			{
				$item->setSelected (true);
				$itemList->setProperty ('select_name',$name);
			}	
			$imgInfo = $userInfo->getImageInfo();
			if ($imgInfo != null)
			{
				$imageFile = GlobalConstants::getAvatarImage($imgInfo->getFile());
				$item->setImage ($imageFile);
				$w = $imgInfo->getWidth();
				$h = $imgInfo->getHeight();
				if ($w != null && $h != null)
				{
					$ar = Utils::calcBoxImageSize ($w, $h, 80, 80);
					$item->setProperty ('image_width', $ar[0]);
					$item->setProperty ('image_height', $ar[1]);
				}
			}
			$itemList->add ($item);
 		}
 		
 		return $itemList;
 	}
    
}
?>