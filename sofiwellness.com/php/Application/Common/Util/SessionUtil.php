<?php
/*
 * Created on Aug 15, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
//session_start();

class SessionUtil
{
    public static function has($name)
    {
        return isset($_SESSION[$name]);
    }
    
    public static function get($name) 
    {
        if(SessionUtil::has($name)) 
        {
            if(SessionUtil::has('__requireFiles')) 
            {
                $requireFiles = unserialize($_SESSION['__requireFiles']);
                if(array_key_exists($name, $requireFiles)) 
                {
                    require_once($requireFiles[$name]);
                }
            }
            
            
            return unserialize($_SESSION[$name]);
        }
        
        return null;
    }
    
    public static function set($name, $value)
    {
    	SessionUtil::put ($name, $value);
    }
    
    public static function put($name, $value) 
    {
        if(is_object($value)) 
        {
            if(method_exists($value, 'getClassFile')) {
                if(!($requireFiles = Session::get('__requireFiles')))
                    $requireFiles = array();
                
                $requireFiles[$name] = $value->getClassFile();
                Session::put('__requireFiles', $requireFiles);
            }
        }
        
        $_SESSION[$name] = serialize($value);
    }
    
    public static function remove ($name)
    {
    	if ($name != null && SessionUtil::has($name))
    		unset($_SESSION[$name]);
    }
}

?>