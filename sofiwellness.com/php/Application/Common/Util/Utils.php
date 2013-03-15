<?php
/*
 * Created on May 1, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


require_once 'Zend/Locale.php';

require_once 'SessionUtil.php';
require_once APP_COMMON_DIR .'/Models/GlobalConstants.php';
require_once APP_COMMON_DIR .'/Models/Map.php';
require_once APP_ROOT . '/Application/UserMgmt/UserInfo.php';
 
class Utils
{
	const DATETIME_PATTEN = 'Y-m-d H:i:s';
	const SPACE = '&nbsp;';
	
	public static function getUserId ()
	{
		//$userId = ServerParams::getUserId();
		$userId = SessionUtil::get(GlobalConstants::USER_ID);
		return $userId;
	}
	
	public static function getUserName ()
	{
		$userName = SessionUtil::get (GlobalConstants::USER_NAME);
		if ($userName == null)
		{
			if (SessionUtil::has('userInfo'))
    		{
    		
    		//Log::debug ('has userInfo in session');
    		$userInfo = SessionUtil::get('userInfo');
    	    $userName = $userInfo->getUserName();
    		//Log::debug('userInfo - username=' .$userInfo->getName());
    		}
		}
		//Log::debug('userName:' . $userName);
		
		return $userName;
	}
	
	public static function getAccountType ()
	{
		return SessionUtil::get (GlobalConstants::ACCOUNT_TYPE); 
	}
	

	
	public static function getUserInfo ()
	{
		if (SessionUtil::has(GlobalConstants::USER_INFO))
			return SessionUtil::get(GlobalConstants::USER_INFO);
		else
			return null;
	}
	
	public static function getCurrentTime()
	{
		$timeNow = date (Utils::DATETIME_PATTEN);
		return $timeNow;
	}
	
	public static function getToday ()
	{
		return $today = getdate(time()); 
	}
	
	//return 1 if $start_date > $end_date
	public static function greaterDate($start_date,$end_date)
	{
  		$start = strtotime($start_date);
  		$end = strtotime($end_date);
  		if ($start-$end > 0)
    		return 1;
  		else
   			return 0;
	}

	
	public static function extractDescAsToolTip ($desc)
	{
		if ($desc == null)
			return null;
			
		return Utils::stripSelectedTags($desc);
	}
	
	public static function getOpenLinkNewWindowScript ()
	{
		$html = 'onclick="window.open(this.href);return false;"';
		return $html;		
	}
	
	public static function getOpenNewWindowScript ($href)
	{
		$html = 'onclick="window.open(\'' .$href .'\');return false;"';
		return $html;				
	}
	
	public static function getPredefinedLengthString ($text, $len)
   	{
   		if ($text == null)
   			return $text;
   		
   		if (strlen ($text) > $len)
   			return substr ($text, $len);
   		else
   			return $text;	
   	}
   	
   	public static function replaceLineBreakWithHtmlTags ($text)
   	{
   		//$replace = array ( "<br>");
		$search = array("\r\n", "\n", "\r");
	    $text1 = str_replace ($search, "<br/>", $text);
   		return $text1;
   	}
   	
	public static function calcImageHeightByRatio ($widthRatio, $width, $height)
	{
		$h =  ($widthRatio * $height) / $width;
		return $h;
	}
	
	public static function calcImageWidthByRatio ($heightRatio, $width, $height)
	{
		$w =  ($heightRatio * $width) / $height;
		return $w;
	}
	
	//return array(width, height)
	public static function calcBoxImageSize ($imageWidth, $imageHeight, $boxWidth, $boxHeight)
	{
		//Log::debug ('image: w=' .$imageWidth .', h=' .$imageHeight);
		//try fit to width first
		$ratio = $boxWidth / $imageWidth;
      	$height = intval($imageHeight * $ratio);
		$width = $boxWidth;
 		if ($height > $boxHeight)
		{
			$ratio = $boxHeight / $imageHeight;
      		$width = intval($imageWidth * $ratio);
			$height = $boxHeight;
		}
		
		
		return array($width, $height);
	}
	
	//Test if a string starts with a given string, needle is the search for, $haystack is the text
	public static function startsWith($text, $needle)
    {
    	return strpos($text, $needle) === 0;
    }
    
    
    public static function endsWith($haystack, $needle) 
    {     
    	//$needle = preg_quote( $needle);
  		//return preg_match( '/(?:$needle)\$/i', $haystack);
    	 
    	$len = strlen($needle); 
    	$string_end = substr($haystack, strlen($haystack) - $len); 
    	return $string_end == $needle; 
    }
    
    public static function stringTokenize($sBuffer, $sSplit) 
    {
        $iCount = 0;
        
        if(strlen($sBuffer) == 0)
            return null;
        
        $sToken = strtok($sBuffer, $sSplit);
        $aTokens[$iCount] = $sToken;

        while ($sToken !== false) {
            $sToken = strtok($sSplit);
            if(strlen($sToken) > 0) {
                $iCount++;
                $aTokens[$iCount] = $sToken;
            }
        }    // end while
        return $aTokens;
    } 
    
    public static function charAt($str, $pos)
    {
    	return ($pos < 0 || $pos >= strlen($str)) ? -1 : $str{$pos};
	}
	
	//make abstract - first strip off the tags, then cut off those > maxLength
	public static function getArticleAbstract ($content, $maxLength=250)
	{
		if ($content == null)
			return $content;
			
		$txt = $content;
		if (strlen ($txt) > $maxLength)	
		{ 
			$txt = strip_tags($txt);
			$txt = substr ($txt, 0, $maxLength);
			$size = strlen ($txt);
			$pos = strrpos ($txt, ' ');
			$txt = substr ($txt, 0, $pos);
				
		}
		return $txt;			
	}	
	
	//needle is array
	public static function substrArrayCount($haystack, $needle) 
	{
	     $count = 0;
	     foreach ($needle as $substring) 
	     {
	          $count += substr_count( $haystack, $substring);
	     }
	     return $count;
	}
	
    public static function stringToArray ($str, $needle)
    {
    	$arrayTag = split($needle, $str);
    	return $arrayTag;
    }


	public static function getUrlDomainName ($url)
	{
		$hostname = parse_url($url, PHP_URL_HOST);
		if (Utils::startsWith($hostname, "www."))
			$hostname = substr ($hostname, 4);
			
		if (false !== strpos ($hostname, "."))
		{
			$n = strrpos ($hostname, ".");
		    $hostname = substr ($hostname, 0, $n);
		}
		return $hostname;
	}
	
	public function deleteFile($file) 
	{
		$ofile = fopen($file, 'w');
		fclose($ofile);
		unlink($file);
	} 

	
	
	public static function sendFile($path) 
	{
	    session_write_close();
	    ob_end_clean();
	    if (!is_file($path) || connection_status()!=0)
	    {
	        return(FALSE);
	    }
	    
	    //to prevent long file from getting cut off from     //max_execution_time
	    set_time_limit(0);
	
	    $name=basename($path);
	
	    //filenames in IE containing dots will screw up the
	    //filename unless we add this
	    if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
	        $name = preg_replace('/\./', '%2e', $name, substr_count($name, '.') - 1);
	
	    //required, or it might try to send the serving     
	    //document instead of the file
	    header("Cache-Control: ");
	    header("Pragma: ");
	    header("Content-Type: application/octet-stream");
	    header("Content-Length: " .(string)(filesize($path)) );
	    header('Content-Disposition: attachment; filename="'.$name.'"');
	    header("Content-Transfer-Encoding: binary\n");
	
	    if($file = fopen($path, 'rb'))
	    {
	        while( (!feof($file)) && (connection_status()==0) )
	        {
	            print(fread($file, 1024*8));
	            flush();
	        }
	        fclose($file);
	    }
	    return((connection_status()==0) and !connection_aborted());
	}

	public static function createRandomString ($length)
	{
		$captcha_chars = array('2','3','4','5','6','7','8','9',
         'A','B','C','D','E','F','G','H','J','K','M','N','P','Q','R','S','Y','U','V','W','X','Y','Z');
	    
	    $text = '';
	    for($i = 0; $i < $length; $i++)
	    {
	        $rand_char = rand(0, (count($captcha_chars) -1));
	        $text .= $captcha_chars[$rand_char];
	    }
		
		return $text;
	}	
	
	//$key is the salt, $data is the string to be crypted
	//this is compatible with md5.js: hex_hmac_md5 function
	public static function hmac ($key, $data)
	{
	    // RFC 2104 HMAC implementation for php.
	    // Creates an md5 HMAC.
	    // Eliminates the need to install mhash to compute a HMAC
	    // Hacked by Lance Rushing
	
	    $b = 64; // byte length for md5
	    if (strlen($key) > $b) {
	        $key = pack("H*",md5($key));
	    }
	    $key  = str_pad($key, $b, chr(0x00));
	    $ipad = str_pad('', $b, chr(0x36));
	    $opad = str_pad('', $b, chr(0x5c));
	    $k_ipad = $key ^ $ipad ;
	    $k_opad = $key ^ $opad;
	
	    return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
	}
	
	public static function br2nl($text) 
	{    
		return  preg_replace('/<br\\s*?\/??>/i', "\n", $text);
	}
	
	public static function isOdd ($number)
	{
		if(!($number % 2))
			return true;
		else
			return false;
		
	}
	
	public static function isValidEmailAddress($email)
	{

		$qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';

		$dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';

		$atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.
			'\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';

		$quoted_pair = '\\x5c[\\x00-\\x7f]';

		$domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";

		$quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";

		$domain_ref = $atom;

		$sub_domain = "($domain_ref|$domain_literal)";

		$word = "($atom|$quoted_string)";

		$domain = "$sub_domain(\\x2e$sub_domain)*";

		$local_part = "$word(\\x2e$word)*";

		$addr_spec = "$local_part\\x40$domain";

		return preg_match("!^$addr_spec$!", $email) ? 1 : 0;
	}


	
	public static function generatePassword ($length = 8)
	{
	  	$password = "";
	
	  	// define possible characters
	  	$possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
	    
	  	// set up a counter
	  	$i = 0; 
	    
	  	// add random characters to $password until $length is reached
	  	while ($i < $length) 
	  	{ 	
		    // pick a random character from the possible ones
		    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		        
		    // we don't want this character if it's already in the password
		    if (!strstr($password, $char)) 
		    { 
		      $password .= $char;
		      $i++;
		    }
	  	}
	  	return $password;
	}
	
	public static function setHeaderCache ($seconds=600)
	{
		$cacheCtrl = "Cache-Control: private, max-age=" .$seconds .", pre-check=" .$seconds;
		header("Expires: ".gmdate("D, d M Y H:i:s")." GMT"); // Always expired
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");// always modified 
		//header("Cache-Control: private, max-age=600, pre-check=600");// HTTP/1.1 
		header($cacheCtrl);// HTTP/1.1 
		header("Pragma: nocache");// HTTP/1.0	
	}

	//return Map
	public static function getSimpleFormData ()
	{
    	Log::debug('getSimpleFormData');
    	$data = new Map();
    	foreach ($_POST as $key => $value) 
		{
			if (!is_array($value) && get_magic_quotes_gpc()) 
				$value=stripslashes($value);
				
			if (false === is_array($value))
				$value = trim($value);
			Log::debug('key:'. $key . ', val:' . $value);
			if (is_array($value))
			{
				Log::debug ('value is array');
				$data->put($key, $value);
			}
			else if (strlen($value) > 0)
			{		
				$data->put($key, $value);
			}
		}
		return $data;
	}
	
	
	public static function getFileList ($dir, $underDocRoot=true)
	{
   		$logodir = $dir;
   		if(substr($dir, -1) != "/") 
    		$logodir .= "/";
    	
    	//full server path to directory
    	if ($underDocRoot)
    		$fullpath = "{$_SERVER['DOCUMENT_ROOT']}/$logodir";
    	else
    		$fullpath = $logodir;
    	
     	Log::debug ('full dir:' .$fullpath);
    	
    	//$path = realpath ($fulldir);
    	//Log::debug ('bm logo dir:' .$path);
    	$arrayFile = array();
    	$dir = dir($fullpath);
		while (($file = $dir->read()) !== false)
		{
			if ($file == '.' || $file == '..')
				continue;
				 
			$arrayFile[] = $file;
		}
		$dir->close();		
		
		return $arrayFile;
	}
	//file path related to the application root
	//return array lines  as $line_num=>$text
	public static function readTextFile ($filepath, $underAppRoot=true)
	{
    	if ($underAppRoot)
    	{
    		$appRoot = ServerParams::getAppRoot();
    		if (Utils::startsWith ($filepath, "/"))
    			$sourceFile = $appRoot . $filepath;
    		else
    			$sourceFile = $appRoot .'/' .$filepath;
    	}
    	else
    		$sourceFile = $filepath;
    		
    	$map = new Map();
    	$lines = file($sourceFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
 		return $lines;
	}
	
	public static function loadFile ($file)
	{
		$fd = fopen ($file, "r"); 
		while (!feof ($fd)) 
		{ 
   			$buffer = fgets($fd, 4096); 
   			$lines[] = $buffer; 
		} 
		fclose ($fd); 
		return $lines;
	}
	
	//note: allow tags in array should like $allowed=array('<a>', '<br>')
	public static function stripTagsAndAttributes($sSource, $aAllowedTags = array(), $aDisabledAttributes = null)
    {
    	if ($aDisabledAttributes == null)
    		$aDisabledAttributes = array('onclick', 'ondblclick', 'onkeydown', 'onkeypress', 
				'onkeyup', 'onload', 'onmousedown', 'onmousemove', 'onmouseout', 
				'onmouseover', 'onmouseup', 'onunload');
				
        if (empty($aDisabledAttributes)) 
        	return strip_tags($sSource, implode('', $aAllowedTags));

        return preg_replace('/<(.*?)>/ie', "'<' . preg_replace(array('/javascript:[^\"\']*/i', '/("
        	 .implode('|', $aDisabledAttributes) 
         	 . ")=[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", 
         	 strip_tags($sSource, implode('', $aAllowedTags)));
    }
	
	
	
	///////////////////// for debug or show ////////
	public static function showFormData ()
	{
		 		echo '<table>';
		echo '<tr><th>Field Name</th><th>Value(s)</th></tr>';

		if (empty($_POST)) {
			print "<p>No data was submitted.</p>";
		} 
		else 
		{

			foreach ($_POST as $key => $value) 
			{
				if (!is_array($value) && get_magic_quotes_gpc()) 
					$value=stripslashes($value);
				if ($key=='extras') 
				{
					if (is_array($_POST['extras']) )
					{
						print "<tr><td><code>key:$key</code></td><td>";
						foreach ($_POST['extras'] as $value)
							print "<i>$value</i><br />";
						print "</td></tr>";
					} 
					else 
					{
						print "<tr><td><code>$key</code></td><td><i>$value</i></td></tr>\n";
					}
				} 
				else 
				{
					print "<tr><td><code>$key</code></td><td><i>$value</i></td></tr>\n";
				}
			}
		}

		echo '</table>';
	}
	
	
	
	
	
	public static function getShowFormData ()
	{
		$data = "====== Form Data ==========\n";

		if (empty($_POST)) 
		{
			$data .= "No data was submitted.";
		} 
		else 
		{

			foreach ($_POST as $key => $value) 
			{
				if (!is_array($value) && get_magic_quotes_gpc()) 
					$value=stripslashes($value);
				if ($key=='extras') 
				{
					if (is_array($_POST['extras']) )
					{
						$data .= "$key=";
						foreach ($_POST['extras'] as $value)
							$data .= "$value\n";
					} 
					else 
					{
						$data .= "$key=$value\n";
					}
				} 
				else 
				{
					$data .= "$key=$value\n";
				}
			}
		}

		return $data;
	} 
	
	public static function stripSelectedTags($text, $tags = array())
    {
        $args = func_get_args();
        $text = array_shift($args);
        $tags = func_num_args() > 2 ? array_diff($args,array($text))  : (array)$tags;
        foreach ($tags as $tag)
        {
            if( preg_match_all( '/<'.$tag.'[^>]*>([^<]*)<\/'.$tag.'>/iu', $text, $found) )
            {
                $text = str_replace($found[0],$found[1],$text);
            }
        }
       $newText = preg_replace( '/(<('.join('|',$tags).')(\\n|\\r|.)*\/>)/iu', '', $text);
        
		$replace = array ( "\'", "<br>");
		$search = array("'", "\r\n", "\n", "\r");
	    $text1 = str_replace ($search, $replace, $newText);
	    
	    return $text1;
    }
    
    public static function contains($haystack, $searchFor, $offset=0) 
    { 
        $result = strpos($haystack, $searchFor, $offset); 
        return $result !== FALSE; 
    } 
    
    //haystack is the string 
    public static function strpos($haystack, $searchFor, $offset=0) 
    { 
        $result = strpos($haystack, $searchFor, $offset); 
        if ($result > -1 ) 
        { 
           
            return $result; 
        } 
        return -1; 
    } 
    
    
    //return true or false
    public static function makeDirectory ($path, $mode = 0777)
    {
    	if(is_dir($path))
    		return true;
    		
 		$path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");
    	$e = explode("/", ltrim($path, "/"));
    	if(substr($path, 0, 1) == "/") 
    	{
        	$e[0] = "/".$e[0];
    	}
    	$c = count($e);
    	$cp = $e[0];
    	for($i = 1; $i < $c; $i++) 
    	{
    		Log::debug ('dir:' .$cp);
        	if(!is_dir($cp) && !@mkdir($cp, $mode)) 
        	{
        		Log::debug ('failed here:' .$cp);
            	return false;
        	}
        	$cp .= "/".$e[$i];
    	}
    	if (@mkdir($path, $mode))
    		return true;
    	else
    		return false;
    }
    

	
	///////////////////////////////////////////////////////////////////
	//private
	
}
 
?>
