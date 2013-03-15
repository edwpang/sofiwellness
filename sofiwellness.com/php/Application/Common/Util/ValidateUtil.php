<?php
/*
 * $Id:$
 * FILE:ValidateUtil.php
 * CREATE: Jul 16, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class ValidateUtil
{
	/**
	 * return true if validate
	 */
	public static function validatePhoneNumber ($phone)
	{
		if ($phone == null)
			return false;
			
		$replace = array( ' ', '-', '/', '(', ')', ',', '.' );
 		$ret = preg_match( '/1?[0-9]{10}((ext|x)[0-9]{1,4})?/i', str_replace( $replace, '', $phone)); 
				
		if ($ret != 0)		
			return true;
		else
			return false;
	}
	
	
	//to format: XXX-XXX-XXXX
	public static function toPhoneFormat ($phone)
	{
		if ($phone == null)
			return null;
		
		$phone = trim ($phone);
		$replace = array( ' ', '-', '/', '(', ')', ',', '.' );
		$str = str_replace( $replace, '', $phone);
		$number = substr($str, 0, 3). '-' .substr($str, 3, 3) .'-' .substr($str, 6);
		$pos = Utils::strpos ($number, 'ext');
		if (-1 != $pos)
		{
			$number = str_replace ('ext', 'x', $number);
		}
			
		Log::debug ($phone . ' ->' .$number);
		
		return $number;
	}
}
?>