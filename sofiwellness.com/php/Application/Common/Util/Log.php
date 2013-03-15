<?php

/**********************************************************

File:

Author:guosheng

Date:Aug 14, 2007

********************************************************/

class Log
{
	const LOG_NAME = LOGGER_NAME;
	
	public static function debug ($msg)
	{
		if (LOG_DEBUG) 
		{
			Log::getLogger()->debug ($msg);
		}
	}

	public static function info ($msg)
	{
		$logger = ZendHelper::getLogger(Log::LOG_NAME);
		Log::getLogger()->info ($msg);
	}

	public static function warn ($msg)
	{
		Log::getLogger()->warn ($msg);
	}
	
	public static function error ($msg)
	{
		Log::getLogger()->error ($msg);
	}

	
	public static function fatal ($msg)
	{
		Log::getLogger()->fatal ($msg);
	}
	
	private static function getLogger ()
	{
		$logger = ZendHelper::getLogger(Log::LOG_NAME);
		return $logger;
	}
}

?>