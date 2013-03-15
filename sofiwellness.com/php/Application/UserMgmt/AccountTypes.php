<?php
/*
 * $Id: AccountTypes.php,v 1.2 2009/05/21 19:48:07 gorsen Exp $
 * FILE:AccountTypes.php
 * CREATE: Nov 17, 2008
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class AccountTypes
{
	const CUSTOMER = 4;
	const PROFESSIONAL = 3;
	const BOOK_KEEPER = 2;
	const ADMINISTRATOR = 1;
	
	
	public static function isUndefinedAccount ($type)
	{
		if ($type > AccountTypes::CUSTOMER && $type <= AccountTypes::ADMINISTRATOR)
			return false;
		else
			return true;	
	}
	public static function isCustomerAccount ($type)
	{
		if ($type == null)
			return true;
			
		if ($type == AccountTypes::CUSTOMER)
			return true;
		else
			return false;	
	}	
	
	
	public static function isAdmin ($type)
	{
		if ($type == null)
			return false;
			
		if ($type == AccountTypes::ADMINISTRATOR)
			return true;
		else
			return false;
	}
	
	public static function isProfessional ($type)
	{
		if ($type == AccountTypes::PROFESSIONAL)
			return true;
		else
			return false;
	}
	
	public static function isBookKeeper ($type)
	{
		if ($type == AccountTypes::BOOK_KEEPER)
			return true;
		else
			return false;
	}
	
	public static function getAccountTypeNames ()
	{
		$list = new ArrayList ();
		$list->add ("Customer");
		$list->add ("Professional");
		$list->add ("Book Keeper");
		$list->add ("Administrator");
		
		return $list;
	}
	
	public static function getAccountTypeCode ($name)
	{
		if ($name == null || $name == '')
			return AccountTypes::CUSTOMER;
		if ($name == 'Professional')
			return AccountTypes::PROFESSIONAL;
		else if ($name == 'Book Keeper')
			return AccountTypes::BOOK_KEEPER;
		else if ($name == 'Administrator')
			return AccountTypes::ADMINISTRATOR;
		else
			return AccountTypes::CUSTOMER;
	}
	
	public static function getAccountTypeNameByCode ($code)
	{
		$name = 'Customer';
		if ($code == AccountTypes::PROFESSIONAL)
			$name = 'Professional';
		else if ($code == AccountTypes::BOOK_KEEPER)
			$name = 'Book Keeper';
		else if ($code == AccountTypes::ADMINISTRATOR)
			$name = 'Administrator';
			
		return $name;
	}
}
?>
