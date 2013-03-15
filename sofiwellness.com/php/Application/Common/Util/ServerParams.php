<?php
/*
 * Created on Jan 15, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  
 class ServerParams
 {
 	public static function getDocumentRoot ()
 	{
 		if ( ! isset($_SERVER['DOCUMENT_ROOT'] ) )
  			$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(
   				$_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF']) ) );
		return $_SERVER['DOCUMENT_ROOT'];		
 	}
 	
 	public static function getAppRoot ()
 	{
 		$docRoot = ServerParams::getDocumentRoot();
 		$pos = strrpos ($docRoot, "/");
		$appRoot = substr($docRoot, 0, $pos);
		
		return $appRoot;
 	}
 	
 	public static function getRoot ()
 	{
 		$dir = dirname ($_SERVER['PHP_SELF']);
 		if ($dir == '/' || $dir == '\\')
 			$dir = '';
 				
 		return sprintf("%s://%s:%s%s/",
                   ServerParams::getScheme(), 
                   $_SERVER['SERVER_NAME'],
                   $_SERVER['SERVER_PORT'],
                   $dir);	
 	}
 	
 	public static function getScheme() 
	{
	    $scheme = 'http';
	    if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
	        $scheme .= 's';
	    }
	    return $scheme;
	}
 	
 	public static function getRemoteIP ()
 	{
 		//return $_SERVER['REMOTE_ADDR'];
 		$ip = null;	
		if (isset($_SERVER["REMOTE_ADDR"]))     
		    $ip = $_SERVER["REMOTE_ADDR"]; 
		else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))  
		    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
		else if (isset($_SERVER["HTTP_CLIENT_IP"]))   
		    $ip = $_SERVER["HTTP_CLIENT_IP"]; 
		
		return $ip;
 	}
 	
 	public static function getRequestURI ()
 	{
 		return $_SERVER['REQUEST_URI'];
 	}
 	
 	public static function getQueryString ()
 	{
 		return $_SERVER['QUERY_STRING'];
 	}
 	
 	public static function getQueryParameter ($strParam) 
 	{  	
 		$strQueryString = $_SERVER['QUERY_STRING'];  
 		$aParamList = explode('&',$strQueryString);  
 		$i = 0;  
 		while ($i < count($aParamList)) 
 		{    	
 			$aParam = split('=', $aParamList[$i]);    
 			if ($strParam == $aParam[0]) 
 			{      
 				return $aParam[1];    
 			}     
 			$i++;   
 		}  
 		return "";
 	}
 	
 	public static function getQueryParameters ($toDecode=false)
 	{ 		
 		$params = array();
		$strQueryString = $_SERVER['QUERY_STRING'];  
			
 		$aParamList = explode('&',$strQueryString);  
 		$i = 0;  
 		while ($i < count($aParamList)) 
 		{    	
 			$aParam = split('=', $aParamList[$i]);  
 			$value = null;
 			if ($toDecode)
 				$value = urldecode ($aParam[1]);
 			else
 				$value = $aParam[1];
 			$params[$aParam[0]] = $value;
 			$i++;   
 		}  
 		return $params; 		
 	}
 	
 	//return array
 	public static function getQueryParameters0 ($toDecode=false)
 	{
 		
 		$params = array();
		$strQueryString = $_SERVER['QUERY_STRING'];  
			
 		$aParamList = explode('&',$strQueryString);  
 		$i = 0;  
 		while ($i < count($aParamList)) 
 		{    	
 			$aParam = split('=', $aParamList[$i]);  
 			$value = null;
 			if ($toDecode)
 				$value = urldecode ($aParam[1]);
 			else
 				$value = $aParam[1];
 			$params[$aParam[0]] = $value;
 			$i++;   
 		}  
 		return $params; 		
 	}
 	
 	public static function getRequestParameter ($key)
 	{
 		return $_REQUEST[$key];
 	}
 	
 	public static function setRequestParameter ($key, $val)
 	{
 		$_REQUEST[$key] = $val;
 	}
 	
 	public static function getUserName ()
 	{
 		return $_SESSION['username'];
 	}
 	
 	public static function setUserName ($userName)
 	{
 		$_SESSION['username'] = $userName;
 	}
 	
 	public static function setMainMenubarActiveMenu ($curMenu)
 	{
 		if ($curMenu)	
 			$_SESSION['main_menubar_selected'] = $curMenu;	
 	}
 	
 	public static function getMainMenubarActiveMenu ()
 	{
  		if (isset($_SESSION['main_menubar_selected'])) 
		{
			return $_SESSION['main_menubar_selected'];
		}
		else
			return null;
 	}
 	
 	public static function enableTooltip ($enable)
 	{
 		$_SESSION['enable_tooltip'] = $enable;
 	}
 	
 	public static function isTooltipEnabled ()
 	{
 		if (isset($_SESSION['enable_tooltip']))
 			return $_SESSION['enable_tooltip'];
 		else
 			return false;
 	}
 	
 	public static function setUserId ($userId)
 	{
 		$_SESSION[GlobalConstants::USER_ID] = $userId;
 	}
 	
 	public static function getUserId()
 	{
 		if (isset($_SESSION['userId']))
 			return $_SESSION['userId'];
 		else
 			return null;
 	 
 	}
 }
 
?>
