<?php
/*
 * $Id: DatetimeUtil.php,v 1.5 2010/01/11 15:26:40 gorsen Exp $
 * FILE:Datetime.php
 * CREATE: Feb 17, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class DatetimeUtil
{
	const GMT = 'UCT';
	const DATETIME_PATTEN = 'Y-m-d H:i:s';
	const DATETIME_PATTEN_NS = 'Y-m-d H:i';
	const DATE_PATTEN = 'Y-m-d';
	const SHORT_TIME_PATTEN = 'H:i';
	const PUBDATE_PATTEN = 'Ymd';
	const FULLNAME_DT_PATTEN = 'l, F j, Y g:i A'; //Friday, September 11, 2009 11:30AM
	const FULLNAME_D_PATTEN = 'l, F j, Y';  // Friday, September 11, 2009
	
    private $_daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
 	
	public static function getWeekDayNames ()
	{
			return array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');;
	}
	
	public static function getCodeForWeekDay ($weekdayName)
	{
		$ar = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
		$name = strtolower($weekdayName);
		foreach ($ar as $key => $val)
		{
			if ($name == $val)	
				return $key;
		}
		
		return -1;
	}

	public static function getCurrentTime()
	{
		$timeNow = date (DatetimeUtil::DATETIME_PATTEN);
		return new DateTime('now');
	}
	
	public static function getCurrentTimeStr()
	{
		$timeNow = date (DatetimeUtil::DATETIME_PATTEN);
		return $timeNow;
	}
	
	public static function getCurrentGMTime()
	{
		$utc_str = gmdate(DatetimeUtil::DATETIME_PATTEN, time());
  		$utc = strtotime($utc_str);
		return $utc;
	}	
	
	public static function getCurrentGMDateTime()
	{
		return new DateTime ('now', new DateTimeZone(DatetimeUtil::GMT));		
	}
	
	public static function getCurrentGMTimeStr()
	{
		return gmdate(DatetimeUtil::DATETIME_PATTEN, time());
	}	
	
	public static function toGMTimeStr ($timeStr)	
	{
		$time = strtotime ($timeStr);
		return gmdate(DatetimeUtil::DATETIME_PATTEN, $time);		
	}
	
	public static function toGMTime ($time)	
	{
		return strtotime(gmdate(DatetimeUtil::DATETIME_PATTEN, $time));		
	}
	
	
	public static function getToday ()
	{
		return $today = getdate(time()); 
	}
	
	
	
	public static function getTodayStr()
	{
		$today = new DateTime ('now');
		return $today->format (DatetimeUtil::DATE_PATTEN);
	}
	
	public static function getTodayStrByFormat($format)
	{
		$today = new DateTime ('now');
		return $today->format ($format);
	}
	
	public static function getYesterdayStr ($format=null)
	{		
		if ($format == null)
			$format = DatetimeUtil::DATE_PATTEN;
			
		return date($format, mktime(0,0,0,date("m") ,date("d")-1,date("Y")));
	}
	
	//offsetOfToday: 1: tomorrow, -1: yesterday, 
	public static function getTheDateStr ($offsetOfToday, $format=null)
	{
		if ($format == null)
			$format = DatetimeUtil::DATE_PATTEN;
			
		return date($format, mktime(0,0,0,date("m") ,date("d")+$offsetOfToday ,date("Y")));		
	}
	
	
	public static function getNextDateStr ($theDay, $format=null)
	{
		if ($format == null)
			$format = DatetimeUtil::DATE_PATTEN;
			
		$day = '+1 day';
		
		$date = new DateTime($theDay);
		$date->modify($day);
		return $date->format($format);
	}	
	
	public static function getCurrentWeekStart()
	{
	    if (date("w") == 0) 
		    $adjuster = 6;  
		else 
		    $adjuster = date("w") - 1; 
		$lowdate = date(DatetimeUtil::DATE_PATTEN, strtotime("-" .$adjuster. " days")); 
		return $lowdate;
	}
	
	//0 sunday, 1 monday
	public static function getFirstDateInMonthByWeekDay ($year, $month, $weekday)
	{
		return $num = date("w",mktime(0,0,0,$month, 1, $year));
	}
	
	public static function getAllDatesInMonthByWeekDay ($year, $month, $weekday)
	{
		$firstDate = date("w",mktime(0,0,0,$month, 1, $year));
		$dd = $firstDate - $weekday;
		$d = $weekday + 8 - $dd;
		//Log::debug ('month:' .$month .', dd:' .$dd .', d:' .$d);
		$days = DatetimeUtil::getDaysInMonth($month);
		$ar = array();
		while ($d < $days)
		{
			array_push ($ar, $d);
			$d += 7;
		}
		
		return $ar;
	}
	
	
	//return array contain week start date and end date (from mon - sun)
	public static function getLastWeek ()
	{
		Log::debug ('days:' .date("w"));
	    if (date("w") == 0) 
		    $adjuster = 13;  
		else 
		    $adjuster = date("w") + 6; 
		$start = date(DatetimeUtil::DATE_PATTEN, strtotime("-" .$adjuster. " days")); 
		
		$adjuster -= 6;
		$end = date(DatetimeUtil::DATE_PATTEN, strtotime("-" .$adjuster. " days"));
		Log::debug ('last week:' .$start .' -> ' .$end);
		return array ($start, $end);
	}
	
	//return  start date of the weesks speicified, $weeksFromNow > 0 future, < 0 past
	public static function getWeekDate ($weeksFromNow, $format=DatetimeUtil::DATE_PATTEN)
	{
		$days = $weeksFromNow * 7;
		$theWeek = time() + ( $days * 24 * 60 * 60);
		$start = date($format, $theWeek);
		//$end = date($format, $theWeek + (6 * 24 * 60 * 60));
		return $start;
	}
	
	public static function getWeekNoInMonth() 
	{
		$ar = DatetimeUtil::parseDatetime(DatetimeUtil::getTodayStr());
		$day = $ar['day'];
		//$mon = $ar['month'];
		//$year = $ar['year'];
		$weekNo = ceil($day / 7);
		
		if ($weekNo == 0)
			$weekNo = 1;
		
		return $weekNo;
	}
	
	public static function getWeekdayFromDate ($dateStr)
	{
		$ar = DatetimeUtil::parseDatetime ($dateStr);
		$weekday = date("l", mktime(0,0,0,$ar['month'], $ar['day'],$ar['year']));
		return $weekday;
	}
	
	//return number of month, 1 for jan, ... 12 for dec
	public static function getCurrentMonth ()
	{
		$today = new DateTime ('now');
		return $today->format ('n');		
	}
	
	//return full name of the month
	public static function getCurrentMonthName ()
	{
		$today = new DateTime ('now');
		return $today->format ('F');		
	}
	
	public static function getMonthNameByNumber ($monthNum)
	{
		$monthName = date("F", mktime(0, 0, 0, $monthNum, 10)); 	
		return $monthName;
	}
	
	public static function getMonthNames ($from=null, $end=null)
	{
		$mon =array(
          "January"=>"1",
          "February"=>"2",
          "March"=>"3",
          "April"=>"4",
          "May"=>"5",
          "June"=>"6",
          "July"=>"7",
          "August"=>"8",
          "September"=>"9",
          "Octorber"=>"10",
          "November"=>"11",
          "December"=>"12",
        );
        
        Log::debug ('getMonthName: from:' .$from .', end=' .$end);
        if ($from == null)
        	return $mon;
        
        $ar = array();
		foreach ($mon as $name => $idx)
		{
			if ($end == null || $end < $from)
			{
				if ($idx >= $from)
					$ar[$name] = $idx;
			}
			else
			{
				if ($idx >= $from && $idx <= $end)
					$ar[$name] = $idx;
			}
		}
		
		if ($end < $from)
		{
			foreach ($mon as $name => $idx)
			{
				if ($idx <= $end)	
					$ar[$name] = $idx;
			}	
			
		}
		
		return $ar;
	}
	
	
	public static function addMinutes ($dateStr, $minutes)
	{
		return DatetimeUtil::addMinutesWithFormat($dateStr, $minutes, null);
	}
	
	public static function addMinutesWithFormat ($dateStr, $minutes, $format)
	{
		$mAdd = '+' .$minutes . 'minute';
		$date = new DateTime($dateStr);
		$date->modify($mAdd);
		if ($format == null)
			$format = DatetimeUtil::DATETIME_PATTEN;
		return $date->format($format);
	}
	
	public static function addDays ($dayStr, $daysToAdd, $sym ='+')
	{
		//$day = '+' .$daysToAdd .' day';
		$day = $sym .$daysToAdd .' day';
		
		$date = new DateTime($dayStr);
		$date->modify($day);
		return $date->format(DatetimeUtil::DATETIME_PATTEN);
	}
	
	//$dt is the string for time
	public static function formatDatetimeStr ($dt, $format)
	{
		return date($format ,strtotime($dt));
	}
	
	
	public static function getFormatDatetimeStr ($dt, $format)
	{
		$day = new DateTime ($dt);
		return $day->format ($format);		
	}

	public static function addMonths ($dayStr, $toAdd)
	{
		$mstr = '+' .$toAdd .' month';
		$date = new DateTime($dayStr);
		$date->modify($mstr);
		return $date->format(DatetimeUtil::DATETIME_PATTEN);
	}

	
	//input gmtime string
	public static function gmToLocalTimeStr ($gmTimeStr, $timezoneId)
	{
		$tz = new DateTimeZone ($timezoneId);
		$time = new DateTime($gmTimeStr, new DateTimeZone('UCT'));
		$time->setTimeZone($tz);
		
		return $time->format(DatetimeUtil::DATETIME_PATTEN);
	}
	
	public static function localToGmTimeStr ($timeStr, $timezoneId)
	{
		//Log::debug ('localToGmTimeStr:' .$timeStr .', timezone:' .$timezoneId);
		$time = new DateTime($timeStr, new DateTimeZone($timezoneId));
		//Log::debug ('tiem:' .$time->format (DatetimeUtil::DATETIME_PATTEN));
		$time->setTimeZone(new DateTimeZone ('UCT'));
		
		return $time->format(DatetimeUtil::DATETIME_PATTEN);
	}
		
	public static function getDateString ($year, $month, $day)
	{
		return sprintf ("%d-%02d-%02d", $year, $month, $day);	
	}
	
	public static function getTimeString ($hour, $minute, $second=null)
	{
		if ($second == null)
		{
			return sprintf('%02d:%02d', $hour, $minute);
		}
		else
		{
			return sprintf('%02d:%02d:%02d', $hour, $minute, $second);
		}
	}
	
	public static function timezoneList ()
	{
		$time_zones = timezone_identifiers_list();
		$time_to_use = 'now'; # just a dummy time
		$time_zone_abbreviations = array();
		foreach ($time_zones as $time_zone_id)
		{
		    $dateTime = new DateTime($time_to_use);
		    $dateTime->setTimeZone(new DateTimeZone($time_zone_id));
		    $abbreviation = $dateTime->format('T');
		    $offset = $dateTime->getOffset() / 60;
		    $dst = $dateTime->format ('I');
		    //Log::debug($offset . ' - ' . $abbreviation . ' (' . $time_zone_id . ')');
		    Log::debug ($time_zone_id .' ' .$abbreviation. ' ' .$offset .' daylightsavingTime:' .$dst);
		}		
		
	}
	
	//return array contains 'year', 'month', 'day', 'hour', 'minute' and 'second' and so on
	public static function parseDatetime ($datetimeStr)
	{
		$time = date_parse($datetimeStr);
		$year = $time['year'];
		$month = $time['month'];
		$date = $time['day'];
		$hour = $time['hour'];
		$min = $time['minute'];
		$sec = $time['second'];
		
		return $time;
	}
	
	
	public static function getDateStr ($year, $month, $date)
	{
		$dateTime = new DateTime ('now');
		$dateTime->setDate ($year, $month, $date);
		return $dateTime->format (DatetimeUtil::DATE_PATTEN);
	}
	
	public static function getDateOnly ($timeStr)
	{
		$ar = DatetimeUtil::parseDatetime ($timeStr);
		return DatetimeUtil::getDateStr ($ar['year'], $ar['month'], $ar['day']);
	}

	public static function getTimeOnly ($timeStr, $excludeSec)
	{
		$ar = DatetimeUtil::parseDatetime ($timeStr);
		$sec = $ar['second'];
		if ($excludeSec)
			$sec = null;
		return DatetimeUtil::getTimeString ($ar['hour'], $ar['minute'], $sec);
	}
	
	//$month from 1-12
	public static function getDaysInMonth ($month)
	{
		$_daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		return $_daysInMonth[$month-1];
	}
	
	public static function getGmTimeZone ()
	{
		return  new DateTimeZone(DatetimeUtil::GMT);
	}
	
	public static function compare ($timeStr1, $timeStr2)
	{
		$time1 = strtotime ($timeStr1);
		$time2 = strtotime($timeStr2);
		$d = $time1 - $time2;
		if ($d > 0)
			return 1;
		else if ($d < 0)
			return -1;
		else
			return  0;
	}
	
	public static function calcNextGmDueDate ($recurrence, $dueDate, $from, $to)
	{
		$dueTime = new DateTime($dueDate, DatetimeUtil::getGmTimeZone());
		$fromTime = new DateTime($from, DatetimeUtil::getGmTimeZone());
		$toTime = new DateTime ($to, DatetimeUtil::getGmTimeZone());
		//Log::debug ('calcNextGmDuDate: dueDate:' .$dueDate.', recurrence:' .$recurrence);
		//Log::debug ('fromTime:' .$from .', to:' .$to);
		
		$year = $fromTime->format('Y');
		$month = $fromTime->format('m');
		$daysInMonth = DatetimeUtil::getDaysInMonth($month);
		$bMonthly = false;
		$d = 0;
		if ($recurrence == 'daily')
			$d = 1;
		else if ($recurrence == 'weekly')
			$d = 7;
		else if ($recurrence == 'biweekly')
			$d = 14;
		else if ($recurrence == 'monthly')
			$bMonthly = true;
			
		$dayAdd = $d;
		$nextTime = new DateTime($dueDate, DatetimeUtil::getGmTimeZone());
		$tmDue = strtotime($dueTime->format(DatetimeUtil::DATETIME_PATTEN));
		$tmFrom = strtotime($fromTime->format(DatetimeUtil::DATETIME_PATTEN));
		$tmTo = strtotime($toTime->format(DatetimeUtil::DATETIME_PATTEN));
		
		//first, get nextTime up to from time
		while ($tmDue < $tmFrom)
		{
			if ($bMonthly)
				$nextTime->setDate ($nextTime->format('Y'), $nextTime->format('m')+1, $nextTime->format('d'));
			else
				$nextTime->setDate ($nextTime->format('Y'), $nextTime->format('m'), $nextTime->format('d') + $d);
			
			$tmDue = strtotime($nextTime->format(DatetimeUtil::DATETIME_PATTEN));
			if ($tmDue >= $tmFrom)
			{
				if ($bMonthly)
					$nextTime->setDate ($nextTime->format('Y'), $nextTime->format('m')-1, $nextTime->format('d'));
				else
					$nextTime->setDate ($nextTime->format('Y'), $nextTime->format('m'), $nextTime->format('d') - $d);
			}
		}
		
		$list = new ArrayList ();
		//now calculate nextTime until to time
		while ($tmDue < $tmTo)
		{
			if ($bMonthly)
				$nextTime->setDate ($nextTime->format('Y'), $nextTime->format('m')+1, $nextTime->format('d'));
			else
				$nextTime->setDate ($nextTime->format('Y'), $nextTime->format('m'), $nextTime->format('d') + $d);
			$timeStr = $nextTime->format (DatetimeUtil::DATETIME_PATTEN);
			//Log::debug ('due:' .$tmDue .', tmTo:' .$tmTo);
			//Log::debug ($timeStr);
			$tmDue = strtotime($timeStr);	
			if ($tmDue < $tmTo)
				$list->add ($timeStr);
		}
		
		
		return $list;
	}
	
# PHP Calendar (version 2.3), written by Keith Devens
# http://keithdevens.com/software/php_calendar
#  see example at http://keithdevens.com/weblog
# License: http://keithdevens.com/software/license

function generate_calendar($year, $month, $days = array(), $day_name_length = 3, 
             $month_href = NULL, $first_day = 0, $pn = array())
{
	$first_of_month = gmmktime(0,0,0,$month,1,$year);
	#remember that mktime will automatically correct if invalid dates are entered
	# for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
	# this provides a built in "rounding" feature to generate_calendar()

	$day_names = array(); #generate all the day names according to the current locale
	for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
		$day_names[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name

	list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
	$weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
	$title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names

	#Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
	@list($p, $pl) = each($pn); @list($n, $nl) = each($pn); #previous and next links, if applicable
	if($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
	if($n) $n = '&nbsp;<span class="calendar-next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
	$calendar = '<table class="calendar">'."\n".
		'<caption class="calendar-month">'.$p.($month_href ? '<a href="'.htmlspecialchars($month_href).'">'.$title.'</a>' : $title).$n."</caption>\n<tr>";

	if($day_name_length){ #if the day names should be shown ($day_name_length > 0)
		#if day_name_length is >3, the full name of the day will be printed
		foreach($day_names as $d)
			$calendar .= '<th abbr="'.htmlentities($d).'">'.htmlentities($day_name_length < 4 ? substr($d,0,$day_name_length) : $d).'</th>';
		$calendar .= "</tr>\n<tr>";
	}

	if($weekday > 0) $calendar .= '<td colspan="'.$weekday.'">&nbsp;</td>'; #initial 'empty' days
	for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
		if($weekday == 7){
			$weekday   = 0; #start a new week
			$calendar .= "</tr>\n<tr>";
		}
		if(isset($days[$day]) and is_array($days[$day])){
			@list($link, $classes, $content) = $days[$day];
			if(is_null($content))  $content  = $day;
			$calendar .= '<td'.($classes ? ' class="'.htmlspecialchars($classes).'">' : '>').
				($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : $content).'</td>';
		}
		else $calendar .= "<td>$day</td>";
	}
	if($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>'; #remaining "empty" days

	return $calendar."</tr>\n</table>\n";
}


}
?>
