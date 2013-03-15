<?php
/*
 * $Id:$
 * FILE:ServiceTimeHelper.php
 * CREATE: Jun 8, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */

require_once APP_APPOINTMENT_DIR .'/Controllers/AppointmentInclude.php';

class ServiceTimeHelper
{
	public static function getOffDateList ($year, $month)
	{
		//Log::debug ('year:' .$year .', $month:' .$month);
		$list = new ArrayList(); //containing day from 1 - 31
		$listInfo = ServiceTimeHelper::getGeneralWeekOffDates ();
		if ($listInfo != null)
		{
			$count = $listInfo->size ();
			for ($i = 0; $i < $count; $i++)
			{
				$info = $listInfo->get($i);	
				$theDate = $info->getTheDate(); //it is sunday ...
				$code = DatetimeUtil::getCodeForWeekDay ($theDate);
				$ar = DatetimeUtil::getAllDatesInMonthByWeekDay ($year, $month, $code);
				foreach ($ar as $key => $val)
				{
					$list->add ($val);	
				}
			}	 
		}
		
		$listInfo = ServiceTimeHelper::getGeneralOffHolidays ();
		if ($listInfo != null)
		{
			$count = $listInfo->size ();
			for ($i = 0; $i < $count; $i++)
			{
				$info = $listInfo->get($i);	
				$holidayName = $info->getTheDate();
				$date = CanadaHolidayCalculator::getDateByNameYear ($year, $holidayName);
				$ar = DatetimeUtil::parseDatetime ($date);
				if ($ar['year'] == $year && $ar['month'] == $month)
					$list->add ($ar['day']);
			}			
		}
		
		return $list;
	}
	
	public static function isOffDay ($date)
	{
		//is holiday
		$holiday = CanadaHolidayCalculator::holiday($date);
		if ($holiday != null)
		{
			$list = ServiceTimeHelper::getGeneralOffHolidays();	
			if ($list != null && $list->size() > 0)
			{
				$count = $list->size();
				for ($i = 0; $i < $count; $i++)
				{	
					$servicetimeInfo = $list->get($i);	
					if ($holiday == $servicetimeInfo->getTheDate())
						return true;
				}
			}	
		}		
		//is business off day such as weeken
		$wDay = DatetimeUtil::formatDatetimeStr ($date, 'l');
		$wDay = strtolower($wDay);
		$list = ServiceTimeHelper::getGeneralWeekOffDates();
		//Log::debug ('wDay:' .$wDay .', list size:' .$list->size());
		if ($list != null && $list->size() > 0)
		{
			$count = $list->size();
			for ($i = 0; $i < $count; $i++)
			{	
					$servicetimeInfo = $list->get($i);	
					if ($wDay == $servicetimeInfo->getTheDate())
						return true;
			}
		}	
		
		return false;
	}
	
	public static function isOffTimeByResource ($resId, $date, $startTime, $endTime)
	{
		$bOff = false;
		//first get the service time see if out of service time
		$info = ServiceTimeHelper::getServiceTimeByResource ($resId, $date);
		if ($info != null)
		{
			$start = $info->getStartTime();
			$end = $info->getEndTime();
			//Log::debug ('start:' .$start .', end:' .$end);
			if ($endTime <  $start || $startTime > $end)
				$bOff = true;
		}
		
		//now check off day
		if (!$bOff)
		{
			$info = ServiceTimeHelper::getOffTimeByResource ($resId, $date);
			
			if ($info != null)
			{
				//Log::debug ('after getOffTime:' .$info->getId());
				$start = $info->getStartTime();
				$end = $info->getEndTime();
				//Log::debug ('start:' .$start .', end:' .$end .'    sTime:' .$startTime);
				if ($startTime < $end || $endTime > $start)
					$bOff = true;			
			}
		}
			
		return $bOff;
	}
	
	
	//////////////////////////////////// for general hour and date ///////////////
	
	//return ServiceTimeInfo
	public static function getGeneralBusinessHour ()
	{
		$db = new ServiceTimeDbAccessor();
		$list = $db->getServiceTimeInfoListByType(0, ServiceTimeInfo::BUSINESS_HOUR);
		if ($list != null && $list->size () > 0)
			return $list->get (0);
		else
			return null;
	}
	
	public static function getGeneralSaturdayBusinessHour()
	{
		$db = new ServiceTimeDbAccessor();
		$list = $db->getServiceTimeInfoListByType(0, ServiceTimeInfo::BUSINESS_HOUR_SAT);
		if ($list != null && $list->size () > 0)
			return $list->get (0);
		else
			return null;
	}

	public static function getGeneralSundayBusinessHour()
	{
		$db = new ServiceTimeDbAccessor();
		$list = $db->getServiceTimeInfoListByType(0, ServiceTimeInfo::BUSINESS_HOUR_SUN);
		if ($list != null && $list->size () > 0)
			return $list->get (0);
		else
			return null;
	}
	
	
	//return list of ServiceTimeInfo for off day - saturday or sunday ...
	public static function getGeneralWeekOffDates ()
	{
		$db = new ServiceTimeDbAccessor();
		return $db->getServiceTimeInfoListByType (0, ServiceTimeInfo::BUSINESS_DAY);	
	}
	
	
	
	public static function isSaturdayOff ()
	{
		$list = ServiceTimeHelper::getGeneralWeekOffDates();
		Log::debug ('isSaturdayOff::list size:' .$list->size());
		if ($list == null || $list->size() == 0)
			return false;
		
		$count = $list->size();
		for ($i = 0; $i < $count; $i++)
		{
			$info = $list->get($i);
			Log::debug ('the date:' .$info->getTheDate());
			if ('saturday' == $info->getTheDate ())
				return true;
		}
		return false;
	}
	
	public static function isSundayOff ()
	{
		$list = ServiceTimeHelper::getGeneralWeekOffDates();
		if ($list == null || $list->size() == 0)
			return false;
		
		$count = $list->size();
		for ($i = 0; $i < $count; $i++)
		{
			$info = $list->get($i);
			if ('sunday' == $info->getTheDate ())
				return true;
		}
		return false;
	}
	
	//return ServiceTimeInfo for off holiday names
	public static function getGeneralOffHolidays ()
	{
		$db = new ServiceTimeDbAccessor();
		return $db->getServiceTimeInfoListByType (0, ServiceTimeInfo::HOLIDAY);	
	}


	//return list of userId who are on duty
	public static function getResourceOnDuty ($date)
	{
		//Log::debug ('ServiceTimeHelper::getResourceOnDuty');
		
		//usually on duty specified by week day
		$day = DatetimeUtil::formatDatetimeStr ($date, "l");
		$day = strtolower ($day);
		$db = new ServiceTimeDbAccessor ();
		$listInfo = $db->getServiceTimeListByResType($day, null, 1, ServiceTimeInfo::GENERAL);
		if ($listInfo == null)
			return null;
		$list = new ArrayList ();
		$count =  $listInfo->size();
		for ($i = 0; $i < $count; $i++)
		{
			$info = $listInfo->get($i);	
			$resId = $info->getUserId ();
			$list->add ($resId);
		}
		
		return $list;
	}
	//return userId of the resource who is day off
	public static function getResourceIdListForDayOff ($dateOff)
	{
		//Log::debug ('ServiceTimeHelper::getResourceIdListForDayOff: dateOff=' .$dateOff);
		$list = null;
		$db = new ServiceTimeDbAccessor();
		$listInfo = $db->getResourceServiceTimeListByDateType($dateOff, ServiceTimeInfo::OFF);
		if ($listInfo != null && $listInfo->size() > 0)
		{
			$list = new ArrayList();
			$count = $listInfo->size();
			for ($i = 0; $i < $count; $i++)
			{
				$info = $listInfo->get($i);
				if (!$info->isOn())
					$list->add ($info->getUserId());
			}
		}
		
		return $list;
	}	
	
	//return serviceTimeInfo
	public static function getServiceTimeByResource ($resId, $date)
	{
		$day = DatetimeUtil::formatDatetimeStr ($date, "l");
		$day = strtolower ($day);
		$db = new ServiceTimeDbAccessor ();
		$info = $db->getServiceTimeInfoByDateType($resId, $day, 1, ServiceTimeInfo::GENERAL);
		return $info;
	}
	

	public static function getOffTimeByResource ($resId, $date)
	{
		$db = new ServiceTimeDbAccessor ();
		$info = $db->getServiceTimeInfoByDateType($resId, $date, 0, ServiceTimeInfo::DAY_OFF);	
		return $info;	
	}
	
	
	public static function deleteServiceTimeByUserId ($userId)
	{
	  	$db = new ServiceTimeDbAccessor();
    	$db->deleteServiceTimeByUserId ($userId);
	}
}
?>