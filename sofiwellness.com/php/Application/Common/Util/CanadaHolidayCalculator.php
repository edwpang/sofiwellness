<?php
/*
 * $Id:$
 * FILE:CanadaHolidayCalculator.php
 * CREATE: Jun 1, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */

require_once 'HolidayNames.php';
require_once APP_COMMON_DIR .'/Models/Map.php';

class CanadaHolidayCalculator
{
	//return list of holiday: key - holiday name: val - holiday date
	public static function getAllHolidays ($year)
	{
		if ($year == null)
			$year = date('Y');
			
		$map = new Map();
		$map->set (HolidayNames::NEW_YEARS, $year.'-01-01');
		$map->set (HolidayNames::FAMILY_DAY, date("Y-m-d", strtotime($year.'-02-00, third monday')));
		$map->set (HolidayNames::GOOD_FRIDAY, date("Y-m-d", strtotime("-2 days", (easter_date($year)))));
		$map->set (HolidayNames::VICTORIA_DAY, date("Y-m-d", strtotime($year.'-05-25, last monday')));
		$map->set (HolidayNames::CANADA_DAY, $year.'-07-01');
		$map->set (HolidayNames::CIVIC, date("Y-m-d", strtotime($year.'-08-00, first monday')));
		$map->set (HolidayNames::LABOR, date("Y-m-d", strtotime($year.'-09-00, first monday')));
		$map->set (HolidayNames::THANKSGIVING, date("Y-m-d", strtotime($year.'-10-00, second monday')));
		$map->set (HolidayNames::CHRISTMAS, $year.'-12-25');
		$map->set (HolidayNames::BOXING_DAY, $year.'-12-26');
		
		return $map;
	}
	
	public static function getHolidayNames ()
	{
		$year = date('Y');
		$map = CanadaHolidayCalculator::getAllHolidays($year);	
		return $map->getKeys ();
	}
	
	public static function getDateByName ($holidayName)
	{
		$year = date('Y');
		$map = CanadaHolidayCalculator::getAllHolidays($year);	
		return $map->get($holidayName);
	}
	
	public static function getDateByNameYear ($year, $holidayName)
	{
		$map = CanadaHolidayCalculator::getAllHolidays($year);	
		return $map->get($holidayName);
	}
	
	
	/**
	 * date is yyyy-mm-dd
	 */
 	public static function holiday($date) 
 	{         
 		$year = substr($date, 0, 4);       
 		switch($date) 
 		{   		
	 		case $year.'-01-01':			
	 			$holiday = 'New Years Day';		
	 		break;				
	 		case date("Y-m-d", strtotime($year.'-02-00, third monday')):			
	 			$holiday = 'Family Day';		
	 		break;				
	 		case date("Y-m-d", strtotime("-2 days", (easter_date($year)))):			
	 			$holiday = 'Good Friday';		
	 		break;				
	 		case date("Y-m-d", strtotime($year.'-05-25, last monday')):			
	 			$holiday = 'Victoria Day';		
	 		break;				
	 		case $year.'-07-01':			
	 			$holiday = 'Canada Day';		
	 		break;				
	 		case date("Y-m-d", strtotime($year.'-08-00, first monday')):			
	 			$holiday = 'Civic Holiday';		
	 		break;				
	 		case date("Y-m-d", strtotime($year.'-09-00, first monday')):			
	 			$holiday = 'Labour Day';		
	 		break;				
	 		case date("Y-m-d", strtotime($year.'-10-00, second monday')):			
	 			$holiday = 'Thanksgiving Day';		
	 		break;				
	 		case $year.'-12-25':			
	 			$holiday = 'Christmas';		
	 		break;				
	 		case $year.'-12-26':			
	 			$holiday = 'Boxing Day';		
	 		break;				
	 		default:			
	 			$holiday = null;				
	 		}		
	 		return $holiday;
	 }
}	
?>