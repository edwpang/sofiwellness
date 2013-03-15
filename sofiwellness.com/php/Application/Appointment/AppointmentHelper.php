<?php
/*
 * $Id: ReminderHelper.php,v 1.3 2009/05/21 19:47:09 gorsen Exp $
 * FILE:ReminderHelper.php
 * CREATE: Feb 20, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once APP_MAIN_PATH .'/Appointment/Controllers/AppointmentInclude.php';
require_once APP_USERMGMT_DIR .'/UserMgmtInclude.php';

class AppointmentHelper 
{
	const TIME_INTERVAL = 30;
	const TIME_FORMAT = 'g:i a';
	const TIME_START = '8:00';
	const TIME_END = '22:00';
	

	public static function isEditable ($date, $startTime, $endTime, $resourceId, 
						$accountType=AccountTypes::CUSTOMER, $userId=null)
	{
		$curDateTime = DatetimeUtil::getCurrentTimeStr();
		$curDate = DatetimeUtil::getDateOnly($curDateTime);
		$curTime = DatetimeUtil::getTimeOnly ($curDateTime, true);
		if($date < $curDate)
			return false;
			
		if ($date == $curDate && $startTime != null && $startTime <= $curTime)
		{
			return false;
		}
						
		if ($startTime != null && $resourceId != 0)
		{
			//if booked
			if (!AppointmentHelper::checkAvailable ($resourceId, $date, $startTime, $endTime, $userId))
				return false;				
		}
		//Log::debug ($time . ', cur:' .$curTime .':isEditable return true');
		return true;
	}
	
	public static function appointmentInfoToItem ($info, $item)
	{
		$item->setId ($info->getId());
		$item->setName ($info->getSubject());
		$item->setDesc ($info->getDetail());
		$startTime = $info->getStartTime();
			
		//$a = explode (" ", $startTime);
		$item->setProperty ('date', $info->getTheDate());
		$item->setProperty ('start_time', $info->getStartTime());
		$endTime = $info->getEndTime();
		//$a = explode (" ", $endTime);
		$item->setProperty('end_time', $endTime);
		
		return $item;
	}
	
	public static function getDefBizTimeList ($startTime=null, $endTime=null)
	{
		if ($startTime == null)
			$startTime = AppointmentHelper::TIME_START;
		if ($endTime == null)
			$endTime = AppointmentHelper::TIME_END;
			
		$list = new ArrayList ();
		
		//every half hour
		$etime = date_parse($endTime);
		$eh = $etime['hour'];
		$em = $etime['minute'];
		$stime = date_parse($startTime);
		$h = $stime['hour'];
		$m = $stime['minute'];
		
		while (true)
		{
			$time = date (AppointmentHelper::TIME_FORMAT, mktime ($h, $m));
			//Log::debug ('h:' .$h .', m:' .$m .'    time=' .$time);
			$list->add ($time);
			$m += 30;
			if ($m == 60)
			{
				$m = 0;
				$h++;
			}
			
			if ($h > $eh || ($h == $eh && $m > $em))
				break;
		}
		
		return $list;
	}

	
	public static function getDefTimeList ()
	{
		$list = new ArrayList();
		
		$min_add = AppointmentHelper::TIME_INTERVAL;
		$num = (60 * 24) / $min_add;   
		$dateTime = new DateTime ('now');
		$dateTime->setTime (0, 0, 0);
		for ($i = 0; $i < $num; $i++)
		{
			$str = $dateTime->format (AppointmentHelper::TIME_FORMAT);
			$list->add ($str);
			//Log::debug ($str);
			$dateTime->setTime ($dateTime->format('H'), $dateTime->format('i') + $min_add);
		}
		
		return $list;
	}
	
	
	//return Itemlist contain two items today and tomorrow, which contain subItemList
	public function getAppointmentListForTodayTomorrow($userId)
	{
		
		$ar = AppointmentHelper::getDayStartAndEndTime (2); //today and tomorrow
		$from = $ar[0];
		$to = $ar[1];
		
		$db = new AppointmentInfoDbAccessor ();
		$list = $db->getAppointmentInfoList ($userId, $from, $to);		
		$listRec = AppointmentHelper::getRecurrenceReminderList($userId, $from, $to);
		
		//add to list 
		$list->addList($listRec);
		
		usort ($list->getItems(), array(&$this, "compareDueDate"));
	
		//now make itemlist
		$curDateTime = DatetimeUtil::getCurrentGMDateTime();
		$today = $curDateTime->format (DatetimeUtil::DATE_PATTEN);
		$tDateTime = DatetimeUtil::getCurrentGMDateTime();
		$tDateTime->setDate ($tDateTime->format('Y'), $tDateTime->format('n'), $tDateTime->format('d') + 1);
		$tomorrow = $tDateTime->format (DatetimeUtil::DATE_PATTEN);
		
		$timeZoneId = AppointmentHelper::getUserTimezone();
		$itemList = new ItemList ();
		$itemToday = new Item ();
		$itemToday->setSubItemList (new ItemList());
		$itemToday->setName ($today);
		$todayItemList = $itemToday->getSubItemList();
		$itemList->add ($itemToday);
		
		$itemTomo = new Item ();
		$itemTomo->setSubItemList (new ItemList());
		$itemTomo->setName ($tomorrow);
		$tomoItemList = $itemTomo->getSubItemList();
		$itemList->add ($itemTomo);
		
		$count = $list->size();
		for ($i = 0; $i < $count; $i++)
		{
        	$info = $list->get($i);
        	$item = new Item ();
        	AppointmentHelper::appointmentInfoToItem ($info, $item);
        	$d = $item->getProperty ('date');
        	//Log::debug ('today:' .$today.', tomorrow:' .$tomorrow .', d=' .$d);
        	if ($d == $today)
        		$todayItemList->add ($item);
        	else
        		$tomoItemList->add ($item);			
		}
		
		Log::debug ('today item size:' .$todayItemList->size() .', tomorrow size:' .$tomoItemList->size());
		return $itemList;	
		
		
	}
	
	//return ArrayList with appointInfo 
	public function getAppointmentListForTheMonth ($userId, $year, $month)
	{
		$ar = AppointmentHelper::getMonthStartAndEndTime($year, $month);
		$from = $ar[0];
		$to = $ar[1];
		
		$db = new AppointmentInfoDbAccessor ();
		$list = $db->getAppointmentInfoList ($userId, $from, $to);
		if ($list != null && $list->size() > 1)
			usort ($list->getItems(), array("AppointmentHelper", "compareDueDate"));
		
		return $list;	
	}
	
	//return ArrayList with ReminderInfo for the day (start and end), including reccurreence reminder
	public function getAppointmentListForDay ($userId, $start, $end)
	{
		
		$db = new AppointmentInfoDbAccessor ();
		$list = $db->getAppointmentInfoList ($userId, $start, $end);
			
		$ar = $list->getItems();
		if ($list->size () > 1)
			usort ($ar, array("ReminderHelper", "compareDueDate"));
		return $list;
	}
	
	public static function getAppointmentInfoById ($id)
	{
		$db = new AppointmentInfoDbAccessor ();
		return $db->getAppointmentInfoById($id);
	}
	
	//return list of appointmentinfo
	public static function getAppointmentListByUser ($userId, $firstName, $lastName)
	{
		$db = new AppointmentInfoDbAccessor ();
		return $db->getAppiontmentsByUser($userId, $firstName, $lastName);		
	}
	
	public static function getAppointmentListWithoutUserId ($firstName, $lastName)
	{
		$db = new AppointmentInfoDbAccessor ();
		return $db->getAppiontmentsByUser (null, $firstName, $lastName);				
	}
	
	
	public static function getAppointmentListByResource ($resId, $date, $userId, $resAsName=true)
	{
		Log::debug ('##getAppointmentListByWithWho:resAsName=' .$resAsName);
		$itemList = new ItemList ();
		$db = new AppointmentInfoDbAccessor ();
		$list = $db->getAppointmentListByResourceId ($resId, $date);
		if ($list == null || $list->size() == 0)
			return $itemList;
			
		$count = $list->size();
		for ($i = 0; $i < $count; $i++)
		{
			$info = $list->get($i);	
			Log::debug ('status:' .$info->getStatus());
			if ($info->getStatus() == AppointmentInfo::STATUS_CANCEL)
				continue;
				
			$resId = $info->getResourceId();
			$resName = null;
			if ($resId != null)
				$resName = AppointmentHelper::getAppointmentResourceName ($resId);
				
			$item = new Item ();
			$item->setId ($info->getId());
			$startTime = $info->getStartTime();
			$sTime = DatetimeUtil::getTimeOnly ($startTime, true);
			$endTime = $info->getEndTime();
			$eTime = DatetimeUtil::getTimeOnly ($endTime, true);
			$timeLength = $info->getTimeLength();
			$blocks = ceil($timeLength / 30);
			$item->setProperty ('date', $date);
			$item->setProperty ('start_time', $sTime);
			$item->setProperty ('end_time', $eTime);
			$item->setProperty ('time_block', $blocks);
			$name = $info->getFirstName () .' ' .$info->getLastName ();
			$item->setProperty ('appointmentee', $name);
			$item->setProperty ('res_id', $info->getResourceId());
			if ($resName != null)
				$item->setProperty ('res_name', $resName);
				
			if ($resAsName)
				$item->setName ($resName);
			else
				$item->setName ($name);
			Log::debug ('item name:' .$item->getName());
			$uId = $info->getUserId();
			if ($userId != 0 && $uId == $userId)
				$item->setSelected (true);
			
			$itemList->add ($item);
		}
		
		
		return $itemList;
	}
	
	
	
	public function compareDueDate ($a, $b)
	{
		//Log::debug ('###compareDueDate');
		$aDueDate = $a->getStartTime();
		$bDueDate = $b->getStartTime();
		//Log::debug ('dueday: a:' .$aDueDate .', b:' .$bDueDate);
		if ($aDueDate == $bDueDate)
			return 0;
		if ($aDueDate > $bDueDate)
			return 1;
		else
			return -1;
		
	}
	
	public static function toBizTimeFormat ($time)
	{
		return DatetimeUtil::formatDatetimeStr ($time, AppointmentHelper::TIME_FORMAT);
	}
	
	public static function getCurrentMonthStartAndEndTime ()
	{
		$dateTime = DatetimeUtil::getCurrentGMDateTime();
		$year = $dateTime->format('Y');
		$month = $dateTime->format('n');
		return AppointmentHelper::getMonthStartAndEndTime($year, $month);
	}	
	
	public static function getMonthStartAndEndTime ($year, $month)
	{
		
		$daysInMonth = DatetimeUtil::getDaysInMonth ($month);
		$startDate = DatetimeUtil::getCurrentGMDateTime();
		$startDate->setDate ($year, $month, 1);
		$startDate->setTime (0, 0, 0); 
		$from = $startDate->format (DatetimeUtil::DATETIME_PATTEN);
		
		$endDate = DatetimeUtil::getCurrentGMDateTime();
		$endDate->setDate ($year, $month, $daysInMonth);
		$endDate->setTime (24, 0, 0); 
		$to = $endDate->format (DatetimeUtil::DATETIME_PATTEN);
		
		return array($from, $to);	
	}
	
	public static function getDayStartAndEndTime ($days)
	{
		$startDate = DatetimeUtil::getCurrentGMDateTime();
		$startDate->setTime (0, 0, 0); 
		$from = $startDate->format (DatetimeUtil::DATETIME_PATTEN);
		
		$year = $startDate->format('Y');
		$month = $startDate->format('n');
		$dayNum = $days - 1; //since inlcude today
		$startDate->setDate ($year, $month, $startDate->format('d')+$dayNum);
		$startDate->setTime (24, 0, 0); 
		$to = $startDate->format (DatetimeUtil::DATETIME_PATTEN);
		
		return array($from, $to);	
	}
		

	public static function getResourceList ()
	{
		$listUserInfo = UserManager::getUserInfoListByType (AccountTypes::PROFESSIONAL);
		//getiamge
		if ($listUserInfo != null && $listUserInfo->size() > 0)
		{
			$imageDb = new ImageInfoDbAccessor();
			$count = $listUserInfo->size();
			for ($i = 0; $i < $count; $i++)
			{
				$info = $listUserInfo->get($i);
				$imageList = $imageDb->getImageByUserIdType ($info->getId(),null);
				if ($imageList != null && $imageList->size() > 0)
					$info->setImageInfo ($imageList->get(0));
			}
		}
		
		return $listUserInfo;
	}
	
	public static function getResourceItemList($baseUrl, $selectedId=0, $date=null)
	{
		$listUserInfo = AppointmentHelper::getResourceList();
		if ($listUserInfo == null || $listUserInfo->size () == 0)
			return null;
			
		$offIdList = null;
		if ($date != null)
		{
			$offIdList = AppointmentHelper::getResourceIdListForDayOff ($date);
			//Log::debug ('offIdList size:' .$offIdList->size());
		}
		$itemList = null;
		$itemList = new ItemList ();
		$count = $listUserInfo->size();
		for ($i = 0; $i < $count; $i++)
		{
			$info = $listUserInfo->get($i);
			$bOff = false;
			if ($offIdList != null)
			{
				$idCount = $offIdList->size();
				for ($j= 0; $j < $idCount; $j++)
				{
					if ($info->getId() == $offIdList->get($j))
						$bOff = true;	
				}
			}
			if ($bOff)
				continue;
				
			$item = new Item();
			$title = $info->getTitle();
			$name = null;
			if ($title == null)
				$name = $info->getFirstName();
			else
				$name = $title .' '. $info->getFirstName();
			$item->setName ($name);
			$item->setId ($info->getId());
			$href = $baseUrl .'&res='.$info->getId();
			$item->setHref ($href);
			if ($selectedId != 0 && $selectedId == $info->getId ())
				$item->setSelected (true);
				
			$imgInfo = $info->getImageInfo();
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
	

	
	public static function getDatetime ($date, $time)
	{
		$str = $date ." " .$time;	
		$t = date_parse($str);
		$year = $t['year'];
		$month = $t['month'];
		$date = $t['day'];
		$hour = $t['hour'];
		$min = $t['minute'];
		$sec = $t['second'];
		
		return date(DatetimeUtil::DATETIME_PATTEN, mktime($hour, $min, $sec, $month, $date, $year));
	}
	
	public static function getAppointmentResourceName ($id)
	{
		$name = null;
		$info = UserManager::getUserInfoById ($id);
		if ($info != null)
		{
			$name = $info->getFirstName () . ' ' .$info->getLastName();
		}
		
		return $name;
	}
	
	//check if not booked, return true if OK or false if not 
	public static  function checkAvailable ($resourceId, $date, $startTime, $endTime, $userId=null)
	{
		$db = new AppointmentInfoDbAccessor ();
		return $db->checkAvailable ($resourceId, $date, $startTime, $endTime, $userId);
	}
	
	
	
	public static function serviceTimeInfoToServiceTimeFormItem ($info)
	{
		$item = new ServiceTimeFormItem();
		$item->setDate ($info->getTheDate());
		$start = AppointmentHelper::toBizTimeFormat ($info->getStartTime());
		$end = AppointmentHelper::toBizTimeFormat ($info->getEndTime());
		$item->setStartTime ($start);
		$item->setEndTime($end);
		$item->setNote ($info->getNote());
		$item->setDate ($info->getTheDate());
		$item->setOn ($info->isOnDuty());
		return $item;
	}
	
	
	public static function sendNotifyEmail ($appointmentInfo, $resourceId)
	{
		if ($appointmentInfo == null || $resourceId == 0)
			return false;
		
		//get resource email
		$userInfo = UserManager::getUserInfoById ($resourceId);	
		$email = $userInfo->getEmail();
		if ($email == null)
		{
			Log::error ("No email found for " .$userInfo->getUserName());
			return false;
		}
		
		$name = $appointmentInfo->getFirstName() . ' ' . $appointmentInfo->getLastName();
		//make up subject
		$subject = 'Appointment for ' .$name;
		
		//make up content
		$startTime = AppointmentHelper:: toBizTimeFormat ($appointmentInfo->getStartTime());
		$endTime = AppointmentHelper:: toBizTimeFormat ($appointmentInfo->getEndTime());
		$theDate = $appointmentInfo->getTheDate();
		
		$content = 'Patient Name:' .$name ."\r\n";
		$content .= "Appointment Time:" .$startTime .' - ' .$endTime;
		$content .= "            Date:"	.$theDate;
		
		$emailSender = new EmailSender();
		$header = $emailSender->makeEmailHeader ('system');	
		$msgInfo = $emailSender->createMessageInfo (0, 'system', $email, $subject, $content);
		$emailSender->send ($email, $msgInfo, $header);
		return ($emailSender->getErrorMessage() == null);
	}
	
	///////////////////////////////////////////////////////////////////////////////////////
	// private
	private static function adjectDueDate ($dueDate, $from, $reccurence=ReminderInfo::DAILY)
	{
		$due = $dueDate;
		$dueTime = strtotime($dueDate);
		$fromTime = strtotime($from);
		$diff = $fromTime - $dueTime;
		if ($diff > 86400)  //more than 24 hours
		{
			$dt = new DateTime($dueDate);
			$ft = new DateTime($from);
			$dt->setDate ($ft->format('Y'), $ft->format('n'), $ft->format('d') -1);
			$due = $dt->format(DatetimeUtil::DATETIME_PATTEN);
		}
		//Log::debug ('#due:' .$dueDate .', from:' .$from .', diff:' .$diff .', new due:' .$due);
			
		return $due;
	}
	
}
?>