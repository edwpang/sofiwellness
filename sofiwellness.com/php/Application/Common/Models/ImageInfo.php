<?php
/*
 * Created on Jan 31, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class ImageInfo 
{
	const TYPE_USER = 'user';  //for user account and profile and contact
	const TYPE_CONTACT = 'contact';  //for contact except for own contant
	const TYPE_CONTACT_SYS = 'contact_sys';  //contact image provided by system
	const TYPE_CONTACT_SELF = 'contact_self';  //only for own contact
	const TYPE_CONTACT_ALL = 'contact_all';   //include contact and contact_self
	const TYPE_BM_LOGO = 'bm_logo'; //for bookmark logo
	const TYPE_LOGO = 'logo'; //for logo
	const TYPE_FRONT_PIC = 'front_picture'; //for fist page picture
	const TYPE_AVATAR = 'avatar'; //for avatar
	const TYPE_CONTEST = 'contest'; //for contest
	
	const IMGTYPE_JPEG = 'image/jpeg';
	const IMGTYPE_PJPEG = 'image/pjpeg';
	const IMGTYPE_JPG = 'image/jpg';
	const IMGTYPE_GIF = 'image/gif';
	const IMGTYPE_PNG = 'image/png';
		
	private $_id;
	private $_name;
	private $_userId = null;
	private $_userName = null;
	private $_type; //type_name: TYPE_USER ...
	private $_category = null; 
	private $_ref = null;
	private $_time;
	private $_description;
	private $_tag = null;
	
	//image file properties
	private $_file;
	private $_imgType;
	private $_size;
	private $_imageWidth;
	private $_imageHeight;
	private $_data = null;
	
	//extra properties
	private $_properties;
	
	public function __construct ()
	{
		$this->_properties = array();
	}

	public function setId ($id)
	{
		$this->_id = $id;
	}
	
	public function getId ()
	{
		return $this->_id;
	}

	public function setName ($name)
	{
		$this->_name = $name;
	}

	public function getName ()
	{
		return $this->_name;
	}

	
	public function setUserId ($userId)
	{
		$this->_userId = $userId;
	}
	
	public function getUserId ()
	{
		return $this->_userId;
	}
	
	public function setUserName ($userName)
	{
		$this->_userName = $userName;	
	}
	
	public function getUserName ()
	{
		return $this->_userName;	
	}
	
	//Deprecated
	public function setType ($type)
	{
		$this->_type = $type;
	}
	
	//Deprecated
	public function getType ()
	{
		return $this->_type;
	}
	
	public function setTypeName ($type)
	{
		$this->_type = $type;
	}
	
	public function getTypeName ()
	{
		return $this->_type;
	}
	public function setRef ($ref)
	{
		$this->_ref = $ref;	
	}
	
	public function setCategory ($val)
	{
		$this->_category = $val;	
	}
	
	public function getCategory ()
	{
		return $this->_category;	
	}
	
	public function getRef ()
	{
		return $this->_ref;	
	}

	//Deprecated
	public function setFile ($fileName)
	{
		$this->_file = $fileName;
	}
	
	//depressed
	public function getFile ()
	{
		return $this->_file;
	}
	
	//Deprecated
	public function setSize ($size)
	{
		$this->_size = $size;
	}
	
	//Deprecated
	public function getSize()
	{
		return $this->_size;
	}
	
	//Deprecated
	public function setWidth ($width)
	{
		$this->_width = $width;
	}
	
	//Deprecated
	public function getWidth ()
	{
		return $this->_width;
	}
	
	//Deprecated
	public function setHeight ($h)
	{
		$this->_height = $h;
	}
	
	//Deprecated
	public function getHeight ()
	{
		return $this->_height;
	}

	//Deprecated
	public function setTime ($t)
	{
		$this->_time = $t;
	}
	
	//Deprecated
	public function getTime ()
	{
		return $this->_time;
	}
	
	public function setImageFile ($file)
	{
		$this->_file = $file;	
	}
	
	public function getImageFile ()
	{
		return $this->_file;	
	}
	
	public function setImageType ($imgType)
	{
		$this->_imgType = $imgType;
	}
	
	public function getImageType ()
	{
		return $this->_imgType;
	}
	
	public function setImageSize ($size)
	{
		$this->_size = $size;
	}
	
	public function getImageSize()
	{
		return $this->_size;
	}
	
	public function setImageWidth ($width)
	{
		$this->_width = $width;
	}
	
	public function getImageWidth ()
	{
		return $this->_width;
	}
	
	public function setImageHeight ($h)
	{
		$this->_height = $h;
	}
	
	public function getImageHeight ()
	{
		return $this->_height;
	}
	
	public function hasData ()
	{
		if ($this->_data != null)
			return true;
		else
			return false;
	}
	public function setData ($content)
	{
		$this->_data = $content;
	}
	
	public function getData ()
	{
		return $this->_data;
	}
	
	//encode the data to base64
	public function encodeData ($data)
	{
		return base64_encode($data);  
	}
	
	public function decodeData ($data)
	{
		return base64_decode($data);  
	}
	
	public function setUpdateTime ($t)
	{
		$this->_time = $t;
	}
	
	public function getUpdateTime ()
	{
		return $this->_time;
	}
	
	public function setDescription ($desc)
	{
		$this->_description = $desc;
	}
	
	public function getDescription ()
	{
		return $this->_description;
	}
	
	public function setTag ($tag)
	{
		$this->_tag = $tag;	
	}
	
	public function getTag ()
	{
		return $this->_tag;	
	}
	
	//set properties
	public function setProperty ($name, $value)
	{
		$this->_properties[$name] = $value;
	}
	
	public function getProperty ($name)
	{
		if ($this->_properties != null)
			return $this->_properties[$name];
		else
			return null;
	}

	
	//return height
	public function resizeToWidth($width)
    {
 		$ratio = $width / $this->getWidth();
      	$height = $this->getHeight() * $ratio;
      	return $height;
    }
	
	//return width
	public function resizeToHeight($height)
	{
 		$ratio = $height / $this->getHeight();
      	$width = $this->getWidth() * $ratio;
      	return $width;
	}
	
	public function resizeTo ($width, $height)
	{
		//try fit to width first
		$ratio = $width / $this->_width;
      	$h = intval($this->_height * $ratio);
		$w = $width;
 		if ($h > $height)
		{
			$ratio = $height / $this->_height;
      		$w = intval($this->_width * $ratio);
			$h = $height;
		}
			
		return array($w, $h);	
	}
	
	//output to browser
	public function outputImage ()
	{
		$img = imagecreatefromstring ($this->_data);		
		if ($img == false)
		{
			Log::debug ('imagecreatefromstring error');
			return;
		}
		if ($this->_imgType == ImageInfo::IMGTYPE_JPEG ||
			$this->_imgType == ImageInfo::IMGTYPE_JPG ||
			$this->_imgType == ImageInfo::IMGTYPE_PJPEG)
		{
			header ("Content-Type: image/jpeg");        
			imageJPEG ($img);
		}
		else if ($this->_imgType == ImageInfo::IMGTYPE_GIF)
		{
			header ("Content-Type: image/gif");        
			imageGIF ($img);			
		}
		else if ($this->_imgType == ImageInfo::IMGTYPE_PNG)
		{
			header ("Content-Type: image/png");        
			imagePNG ($img);			
		}
		else
		{
			Log::debug ('##undefined image type:' .$this->_imgType);
		}
		
		//need to free?
		imagedestroy($img);
	}
	
	
	public function toString ()
	{
		$str = 'name:' .$this->_name;
		$str .= ', file:' .$this->_file;
		$str .= ', type:' .$this->_imgType;
		$str .= ', width:' .$this->_width;
		$str .= ', height:' .$this->_height;	
		
		return $str;
	}
	
	
	public static function createImageInfo ($path, $file, $imageType, $type, $userId=0)
	{
		$size = getimagesize($path .$file);
	   	list($width, $height) = $size;
	   	$fileSize = filesize($path .$file);
	   			
	   	//Log::debug ($file .', file-size:' .$fileSize .', w=' .$width .', h=' .$height);
	   	$imageInfo = new ImageInfo ();
	   	$imageInfo->setName ($file);
	   	$imageInfo->setFile ($file);
	   	$imageInfo->setWidth($width);
	   	$imageInfo->setHeight($height);
	   	$imageInfo->setSize ($fileSize);
	   	$imageInfo->setType ($type);
	   	$imageInfo->setImageType ($imageType);
	   	$imageInfo->setUserId ($userId);
			
		return $imageInfo;		
		
	}
	
	public static function getImageTypeByExt ($fileName)
	{
		preg_match("/\.(.*?)$/", $fileName, $m);   //Get File extension for a better match
    	$ext = strtolower($m[1]);
    	return 'image/' .$ext;
	}
	
	
	private function testImage ()
	{
		$data = 'iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABl'
       . 'BMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDr'
       . 'EX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r'
       . '8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==';
		$img = base64_decode($data);
		$img = imagecreatefromstring($img);
		if ($img !== false) 
		{
    		header('Content-Type: image/png');
    		imagepng($img);
    		return;
		}
		else 
		{
    		echo 'An error occurred.';
		}
	}
} 
 
?>
