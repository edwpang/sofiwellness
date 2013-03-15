<?php
/*
 * $Id: EditController.php,v 1.3 2009/05/21 19:47:15 gorsen Exp $
 * FILE:EditController.php
 * CREATE: Feb 20, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'AppointmentInclude.php';


class Appointment_EditController extends BaseController
{
	private $_pageSize = GlobalConstants::DEF_PAGE_SIZE;

	public function init()
	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	    $this->view->addScriptPath(APPOINTMENT_MODULE_VIEWS);
        $this->view->title = GlobalConstants::SITE_TITLE .':' .GlobalConstants::APPOINTMENT;
        ServerParams::setMainMenubarActiveMenu (GlobalConstants::TABID_APPOINTMENT);
	}

    public function indexAction()
    {
    	Log::debug ('Appointment_EditController::indexAction');
    	$params = ServerParams::getQueryParameters();
    	$month = $params['m'];
    	$year = $params['y'];
    	$day = $params['d'];
    	$idRes = $params['res'];
    	$date = $params['date'];
    	if ($date != null)
    	{
    		$date = urldecode($date);
    		$ar = DatetimeUtil::parseDatetime ($date);
    		$year = $ar['year'];
    		$month = $ar['month'];
    		$day = $ar['day'];
     	}
    	else
    	{
	    	if ($year == null || $month == null)
	    	{
	    		$dateTime = DatetimeUtil::getCurrentTime();	
	    		$year = $dateTime->format('Y');
	    		$month = $dateTime->format('n');
	    		$day = $dateTime->format('d');
	    	}
    		$date = DatetimeUtil::getDateString($year,$month,$day);
    	}	 
     	
    	//get list of professionals
    	$url = '/appointment/edit?y='.$year.'&m='.$month.'&d='.$day;
    	$listRes = $this->getResourceItemList ($url, $idRes, $date);
    	if ($idRes == null && $listRes != null && $listRes->size() > 0)
    	{
    		if (UserManager::isResource ())
    		{
    			$theUserId = Utils::getUserId();
    			$count = $listRes->size();
    			for ($i = 0; $i < $count; $i++)
    			{
    				$item = $listRes->get($i);
    				if ($theUserId == $item->getId())
    				{
    					$item->setSelected (true);
    					$idRes = $theUserId;
    					break;
    				}
    			}
    		}
    		else
    		{
    			$info = $listRes->get (0);
    			$idRes = $info->getId ();
    			$listRes->get(0)->setSelected(true);
    		}
    	}
    	$userId = Utils::getUserId();
    	$resAsName = true;
    	if (!AccountTypes::isCustomerAccount(Utils::getAccountType()))
    	{
    		$resAsName = false;
    	}
    	Log::debug ('idRes=' .$idRes);
    	$appointmentItemList = AppointmentHelper::getAppointmentListByResource ($idRes, $date, $userId, $resAsName);  	
    	Log::debug ('appointment list size:' .$appointmentItemList->size());
    	$weekday = DatetimeUtil::getWeekdayFromDate ($date);
        Log::debug ('weekday:' .$weekday);
        $wd = strtolower($weekday);
        if ($wd == 'saturday')
        	$timeInfo = ServiceTimeHelper::getGeneralSaturdayBusinessHour();
        else if ($wd == 'sunday')
        	$timeInfo = ServiceTimeHelper::getGeneralSundayBusinessHour();
        else
    		$timeInfo = ServiceTimeHelper::getGeneralBusinessHour();
		$this->view->source_list = $appointmentItemList;
		$this->view->resource_list = $listRes;
    	$this->view->the_date = $date;
    	$this->view->resource_id = $idRes;
    	$this->view->user_id = $userId;
    	$this->view->off_date_list = ServiceTimeHelper::getOffDateList ($year, $month);
    	//Log::debug ('off date size:' .$this->view->off_date_list->size());
    	
    	if ($timeInfo != null)
    	{
    		$this->view->start_time = $timeInfo->getStartTimeStr(AppointmentHelper::TIME_FORMAT);
    		$this->view->end_time = $timeInfo->getEndTimeStr(AppointmentHelper::TIME_FORMAT);
    	}
    	echo $this->view->render('AppointmentEditView.php');
    }
    
    public function bookAction ()
    {
    	Log::debug ('EditController::bookAction');
    	$params = ServerParams::getQueryParameters();
    	$date = $params['date'];
    	$time = $params['t'];
    	if ($time != null)
    		$time = urldecode($time);
    	$idWho = $params['res'];
    	$id = $params['aid'];
    	Log::debug ('date:' .$date .', time:' .$time .', idWho:' .$idWho. ', aId:' .$id);
    	$sTime = AppointmentHelper::getDatetime ($date, $time);
    	$info = new AppointmentForm();
    	if ($id != null)
    	{
    		$appInfo = AppointmentHelper::getAppointmentInfoById($id);
    		$info->makeCopy($appInfo);
    	} 
    	$info->setId ($id);
    	$info->setStartTime ($sTime);
    	$info->setResourceId ($idWho);
    	$info->setTimeFrom ($time);
    	$resName = AppointmentHelper::getAppointmentResourceName ($idWho);
    	$info->setResourceName ($resName);
    	$info->setDay ($date);   	
    	$theUserInfo = Utils::getUserInfo();
    	$isCustomer = false;
    	if (AccountTypes::isCustomerAccount ($theUserInfo->getAccountType()))
    	{
    		$info->setUserInfo ($theUserInfo);	
    		$isCustomer = true;
    	} 
    	else
    	{

    		$uInfo = UserManager::getUserInfoByNames ($info->getFirstName(), $info->getLastName());
    		if ($uInfo != null)
    			$info->setUserInfo ($uInfo);
    	}
    	
    	$viewPage = 'AppointmentFormView.php';
    	if ($id == null && $isCustomer)
    	{
    		//check if user is firsttime make appointment
    		$list = AppointmentHelper::getAppointmentListByUser($theUserInfo->getId(), 
    				$info->getFirstName(), $info->getLastName());
    		Log::debug ('appointment list for user: size:' .$list->size());
    		if ($list == null || $list->size() == 0)
    		{
    			//get status, if it is new patient
    			$status = $theUserInfo->getStatus();
    			if ($status == 0)
    				$viewPage = 'appointment_new_patient.php';
    		}
    	}
    	
    	$this->view->error_message = parent::getErrorMessage(true);
    	$this->view->form = $info;
    	$this->view->startDateTime = $sTime;
    	$this->view->startTime = $time;
    	$this->view->theDate = $date;
    	$this->view->endtime_list = $this->getEndTimeList ($time);
    	$this->view->select_endtime = $this->view->endtime_list->get(1);
    	parent::setLastUrl('/appointment/edit?date='.urlencode($date).'&res='.$idWho);
     	echo $this->view->render($viewPage);
    }

    public function saveAction ()
    {
    	Log::debug ('EditController::saveAction');
    	$form = Utils::getSimpleFormData();
		$day = $form->get('appointment_date');
		$idWho = $form->get('res_id');
		
    	$appInfo = $this->formToAppointmentInfo ($form);
    	//TODO check if the date and time with resource has been booked
    	$pickedDate = $form->get('picked_date');
    	$theDate = $form->get('appointment_date');
    	if ($pickedDate != $theDate)
    	{
    		if (!$this->checkAvailable ($idWho, $appInfo->getStartTime(), $appInfo->getEndTime()))	
    		{
    			parent::setErrorMessage ($theDate .' is not available, please pick another day');
    			$href = '/appointment/book?date='.urlencode($day) .'&id=' .$idWho;
    			$href .= '&t='.urlencode ($form->get('start_time'));
    			$this->_redirect($href);
    			return;
    		}
    	}
    	
    
    	$userInfo = $this->formToUserInfo ($form);
    	$err = $this->saveAppointment ($userInfo, $appInfo);
    	if ($err == null && $idWho != null)
    	{
    		//send email?
     		$bOK = AppointmentHelper::sendNotifyEmail ($appInfo, $idWho);
     		$name = $appInfo->getFirstName() . ' ' . $appInfo->getLastName();
			if ($bOK)
				Log::info ('Send email for appointment for ' .$name);
			else
				Log::warn ('Failed to send email for appointment for ' .$name);  		
    	}
    	$this->_redirect('/appointment/edit?date='.urlencode($day) .'&id=' .$idWho);
    }
    
    
    
    public function deleteAction ()
    {
    	//Log::debug ('Appointment_EditController::DeleteAction');
    	$params = ServerParams::getQueryParameters();
    	$id = $params['id'];
    	Log::debug ('id=' .$id);
    	if ($id != null)
    	{
    		$db = new AppointmentInfoDbAccessor ();
    		//$db->deleteAppointmentInfo($id);	
    		$db->updateAppointmentStatus ($id, AppointmentInfo::STATUS_CANCEL);
    	}
    	
    	$forwardUrl = parent::getLastUrl(true);;
		
    	$this->_redirect($forwardUrl);
    }
    
    
    ///////////////////////////////////////////////////////////////////////
    //private functions
    
	private function setupView ($sourceList, $pageNum, $total, $searchText=null)
	{
    	$this->view->sourceList = $sourceList;
    	$this->view->total = $total;
    	$this->view->pageNum = $pageNum;
    	$this->view->pageSize = $this->_pageSize;
    	$this->view->searchText = $searchText;
	}

    private function setupForm($year, $month, $date, $id)
    {  	
    
    	$this->view->year = $year;
    	$this->view->month =$month;
   		$this->view->date = $date;
   		$this->view->dueDate = DatetimeUtil::getDateStr ($year, $month, $date);
   		
   		$this->view->dueTime = DatetimeUtil::getCurrentTimeStr();
   		
   		$info = $this->getAppointmentInfo($id);
   		$this->view->appointmentInfo = $info;
   		$localTimeStr = null;
   		if ($info != null && $info->getId() != null)
   		{
   			$dueDate = $info->getDueDate();
   			$localTimeStr = $dueDate;
   		}
   		$this->view->editFormName = 'appointment_edit_form';
   		$this->view->editFormUrl = '/appointment/edit/update';
   		if ($localTimeStr != null)
   		{
   			$this->view->dueTime = $this->toTimeStr($localTimeStr);
   		}   		
   		$this->view->url = '/appointment/edit?y=' .$year .'&m='.$month .'&d='.$date;
    	$this->view->curDate = DatetimeUtil::getDateString ($year, $month, $date);    	
    }
    
    private function getAppointmentInfo ($id)
    {
    	if ($id == null)
    	{
    		return new AppointmentInfo();
    	}
    	
    	$db = new AppointmentInfoDbAccessor ();
    	$info = $db->getAppointmentInfoById ($id);
		if ($info == null)
			$info = new AppointmentInfo();
		
		return $info;
    	
    }
    
    private function getAppointmentByDate ($date, $month, $year)
    {
    	//Log::debug ('getReminderByDate');
    	$arTime = $this->getBeginAndEndTime ($date, $month, $year);
    	$start = $arTime[0];
    	$end = $arTime[1];
    	$userId = Utils::getUserId();
     
    	$itemList = new ItemList();
    	//$db = new ReminderInfoDbAccessor ();
    	//$list = $db->getReminderList ($userId, $start, $end);
    	$list = AppointmentHelper::getAppointmentListForDay ($userId, $start, $end);
        $count = $list->size();
       
        //now convert remind info to itemList
        for ($i = 0; $i < $count; $i++)
        {
        	$info = $list->get($i);
        	$item = new Item ();
        	AppointmentHelper::appointmentInfoToItem ($info, $item);
        	$itemList->add($item);       	
        }
        Log::debug ('getAppointmentByDate: size:' .$itemList->size());
        return $itemList;
    }   
    
 	private function saveAppointment ($userInfo, $appointmentInfo)
 	{
 		if ($userInfo != null && $userInfo->getId () == 0)
 		{
  			$phone = $userInfo->getPhone();
  			if ($phone != null)
  			{
  				$number = ValidateUtil::toPhoneFormat($phone);
  				$userInfo->setPhone($number);
  			}
 			if ($phone == null)
 				$phone = $userInfo->getCell();
 				
 				
 			$info = UserManager::getUserInfoByNames ($userInfo->getFirstName(), 
 								$userInfo->getLastName(), $phone);	
 			if ($info == null)
 			{
 				$name = $userInfo->getFirstName() . ' ' .$userInfo->getLastName(); 
 				$userInfo->setUserName ($name);
 				UserManager::saveUserInfo ($userInfo);			
 			}
 		}	
 		
 		$err = null;
 		if ($appointmentInfo != null)
 		{
 			$db = new AppointmentInfoDbAccessor();
 			if ($appointmentInfo->getId () != 0)
 				$db->updateAppointmentInfo($appointmentInfo);
 			else
 				$db->saveAppointmentInfo($appointmentInfo);		
 				
 			$err = $db->getErrorMessage();
 		}
 		
 		return $err;
 	}
 	
 	//service //////////////////
 	
 	//return itemlist 
 	private function getResourceItemList ($url, $selectedId, $date)
 	{
 		$listIds = ServiceTimeHelper::getResourceOnDuty ($date);
 		if ($listIds == null)
 			return null;
 		//check if the date is off for some resource
 			
 		
 		//now get user info and image info and convert to item
 		$imageDb = new ImageInfoDbAccessor();
 		$itemList = new ItemList ();
 		$count = $listIds->size();
 		for ($i = 0; $i < $count; $i++)
 		{
 			$userId = $listIds->get($i);
 			$userInfo = UserManager::getUserInfoById ($userId);
 			if ($userInfo == null)
 				continue;
 			$imageList = $imageDb->getImageByUserIdType ($userInfo->getId(),null);
			if ($imageList != null && $imageList->size() > 0)
				$userInfo->setImageInfo ($imageList->get(0));
			$item = new Item();
			$title = $userInfo->getTitle();
			$name = null;
			if ($title == null)
				$name = $userInfo->getFirstName();
			else
				$name = $title .' '. $userInfo->getFirstName();
			$item->setName ($name);
			$item->setId ($userInfo->getId());
			$href = $url .'&res='.$userInfo->getId();
			$item->setHref ($href);
			if ($selectedId != 0 && $selectedId == $userInfo->getId ())
				$item->setSelected (true);
				
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
    
    //////////// others ////////////////
    private function getBeginAndEndTime ($date, $month, $year)
    {
    	//$start = getdate(mktime(12, 0, 0, $month, 1, $year));
		$start = new DateTime('now', new DateTimeZone('UCT'));
    	$start->setDate ($year, $month, $date);
    	$start->setTime (0, 0, 0);
    	Log::debug ('start:' .$start->format(DatetimeUtil::DATETIME_PATTEN));
    	
		$end = new DateTime('now', new DateTimeZone('UCT'));
    	$end->setDate ($year, $month, $date);
    	$end->setTime (24, 0, 0);
    	Log::debug ('end:' .$end->format(DatetimeUtil::DATETIME_PATTEN));
    	
    	return array($start->format(DatetimeUtil::DATETIME_PATTEN), $end->format(DatetimeUtil::DATETIME_PATTEN));	
    }
    
    private function getCurrentLocalTime($timeZoneId)
    {
    	$date = new DateTime ('now', new DateTimeZone($timeZoneId));
    	$min = $date->format('i');
    	if ($min != '00' || $min != AppointmentHelper::TIME_INTERVAL)
    	{
    		if ($min < AppointmentHelper::TIME_INTERVAL)
    			$date->setTime ($date->format('H'), AppointmentHelper::TIME_INTERVAL);
    		else
    			$date->setTime($date->format('H')+1, 0);
    				
    	}
    	$time = $date->format (AppointmentHelper::TIME_FORMAT);
    	return $time;
    }
    
    private function toTimeStr ($dateStr)
    {
    	$datetime = new DateTime($dateStr);
    	return $datetime->format('h:i A');
    }
    
    private function getGMDueDate ($date, $time, $timeZoneId)
    {
     	$dt = $date .' ' .$time;
    	Log::debug ('dt:' .$dt);
    	return DatetimeUtil::localToGmTimeStr ($dt, $timeZoneId);
    }
    
    private function formToAppointmentInfo ($form)
    {
    	$date = $form->get('appointment_date');
		$timeStart = $form->get('start_time');
		//$startTime = AppointmentHelper::getDatetime ($date, $timeStart);
		$startTime = DatetimeUtil::formatDatetimeStr ($timeStart, DatetimeUtil::SHORT_TIME_PATTEN);
		$timeEnd = $form->get ('end_time');
		$endTime = DatetimeUtil::formatDatetimeStr ($timeEnd, DatetimeUtil::SHORT_TIME_PATTEN);
		
		$to_time=strtotime($endTime); 
		$from_time=strtotime($startTime); 
		$minutes = ($to_time - $from_time) /60;
	
		$info = new AppointmentInfo();
		$info->setId ($form->get('id'));
		$info->setUserId ($form->get('user_id'));
		$info->setTheDate ($date);
		$info->setStartTime ($startTime);  
		$info->setEndTime ($endTime);
		$info->setResourceId ($form->get('res_id'));
		$info->setFirstName ($form->get('first_name'));
		$info->setLastName ($form->get('last_name'));
		$info->setTimeLength($minutes);
		
		  	
		return $info;
    }
    
    private function formToUserInfo ($form)
    {
		$info = new UserInfo();
		$info->setId ($form->get('user_id'));
		$info->setFirstName ($form->get('first_name'));
		$info->setLastName ($form->get('last_name'));
		$info->setPhone ($form->get('phone'));
		$info->setCell ($form->get('cell'));
		$info->setAddress($form->get('address'));
		$info->setAccountType (AccountTypes::CUSTOMER);
		
		return $info;
    }
    
    private function getEndTimeList ($time)
    {
    	$format = AppointmentHelper::TIME_FORMAT;
    	
    	$list = new ArrayList ();
    	$t = DatetimeUtil::addMinutesWithFormat ($time, 30, $format);
    	$list->add ($t);
    	$list->add (DatetimeUtil::addMinutesWithFormat ($time, 60, $format));
    	$list->add (DatetimeUtil::addMinutesWithFormat ($time, 90, $format));
   		$list->add (DatetimeUtil::addMinutesWithFormat ($time, 120, $format));
    	return $list;
    }
    
}
?>