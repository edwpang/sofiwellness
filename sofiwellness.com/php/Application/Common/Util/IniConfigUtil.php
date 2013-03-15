<?php
/*
 * $Id:$
 * FILE:IniConfigUtil.php
 * CREATE: Jun 22, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 * settings.ini 

[General] 
url = "http://www.example.com" 
[Database] 
host = localhost 
username = user 
password = password 
db = cms 
adapter = mysqli 

Using the class  
$settings = Settings::getInstance(path to settings.ini); 
print_r($settings->Database); 

Output:
Array 
( 
    [host] => localhost 
    [username] => user 
    [password] => password 
    [db] => cms 
    [adapter] => mysqli 
) 

 * 
 */
 
 
class IniConfigUtil 
{ 
    private static $_instance; 
    private $_settings; 
    
    private function __construct($ini_file) 
    { 
       // $this->_settings = parse_ini_file($ini_file, true); 
       $this->_settings = $this->parseIniFile ($ini_file);
    } 
    
    public static function getInstance($ini_file) 
    { 
        if(! isset(self::$_instance)) { 
            self::$_instance = new IniConfigUtil($ini_file);            
        } 
        return self::$_instance; 
    } 
    
    public function get($setting) 
    { 
        if(array_key_exists($setting, $this->_settings)) 
        { 
            return $this->_settings[$setting]; 
        } 
           
        foreach($this->_settings as $section) 
        { 
        	if(array_key_exists($setting, $section)) 
                 return $section[$setting]; 
        } 
        
        return null;
    } 
    
    public function getSettings ()
    {
    	return $this->_settings;
    }
    
    
    /////////////////////////////////////////////////
	function parseIniFile($iIniFile)
	{
		$aResult  =
		$aMatches = array();
	
		$a = &$aResult;
		$s = '\s*([[:alnum:]_\- \*]+?)\s*';	
		preg_match_all('#^\s*((\['.$s.'\])|(("?)'.$s.'\\5\s*=\s*("?)(.*?)\\7))\s*(;[^\n]*?)?$#ms', @file_get_contents($iIniFile), $aMatches, PREG_SET_ORDER);
	
		foreach ($aMatches as $aMatch)
		{
			if (empty($aMatch[2]))
				$a [$aMatch[6]] = $aMatch[8];
			else	
			    $a = &$aResult [$aMatch[3]];
		}
	
		return $aResult;
	}


function parseDataFile($pFile) 
{
$aResult =
$aMatches = array();

$a = &$aResult;
$s = '\s*([[:alnum:]_\- \*]+?)\s*';

$RX = '';

$RX .= '[\s\n]*’; // Any space and empty lines, and discard them';
$RX .= '(?:';
$RX .= '(?:\[\s*(?P[^\n]+?)\s*\])'; // if it's a section, match it as “section”
$RX .= '|'; // else…
$RX .= '(?:';
$RX .= '(?P"?)(?P[^\n]+?)(?P=vardelim)'; // get the var name into “var” and it's delim (should there be one) into “vardelim”
$RX .= '\s*=\s*'; // skip the equal sign
$RX .= '(?:'; // two possible layouts:
$RX .= '(?s:"""\n(?P.*?)\n"""\n)'; // multiline, delimited by “”", as in PHP ini files
$RX .= '|'; // or…
$RX .= '((?P["\']?)(?P.*?)(?P=delim))'; // single line, catching the delimiter, which could be ” or '
$RX .= ')';
$RX .= ')';
$RX .= ')';
$RX .= '\s*(?P;[^\n]*?)?$'; // Any comment afterwards gets fetched as “comment”, just in case

preg_match_all('#' .$RX .'#m', @file_get_contents($pFile), $aMatches, PREG_SET_ORDER);

foreach ($aMatches as $aMatch) {
if (empty($aMatch['section'])) $a[ $aMatch['var'] ] = ( !isset($aMatch['val']) ? $aMatch['multiline'] : $aMatch['val'] );
else $a = &$aResult[ $aMatch['section'] ];
}

return $aResult;
}


    
} 

?>