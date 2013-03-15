<?php
/*
 * $Id: FileUploadHandler.php,v 1.5 2009/10/04 00:07:06 gorsen Exp $
 * FILE:FileUploadHandler.php
 * CREATE: Sep 19, 2008
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
 
abstract class FileUploadHandler
{
	private $_tempDir;
	private $_uploadDir;
	private $_isUnderDocRoot = true;
	private $_uploadName = 'upload';
	private $_useType;
	private $_namePrefix = null;
	private $_validFileExt = null; //array contains valid file extension
	private $_maxFileSize = 3000000; //bytes
	private $_loadedFile = null;
	
	
	public function setUploadName ($name)
	{
		$this->_uploadName = $name;
	}
	
	public function setUploadDir ($dir, $isUnderDocRoot=true)
	{
		$this->_uploadDir = $dir;
		$this->_isUnderDocRoot = $isUnderDocRoot;
	}
	
	public function getUploadDir ()
	{
		return $this->_uploadDir;	
	}
	
	public function setUseType ($type)
	{
		$this->_useType = $type;
	}
	
	public function getUseType ()
	{
		return $this->_useType;
	}
	
	public function setNamePrefix ($prefix)
	{
		$this->_namePrefix = $prefix;
	}
	
	public function setValidFileExt ($arrayExt)
	{
		$this->_valieFileExt = $arrayExt;
	}
	
	public function setMaxFileSize ($maxFileSize)
	{
		$this->_maxFileSize  = $maxFileSize;
	}
		
	public function isUploadOK ()
	{
		Log::debug ('uploadname:' .$_FILES[$this->_uploadName]['tmp_name']);
		return ($_FILES[$this->_uploadName]['tmp_name']) ;
		//Log::debug ('uploadname:' .$_FILES[$this->_uploadName]['tmp_name']);
		//return ($_FILES[$this->_uploadName]['tmp_name']) ;
	}	
	
	public function deleteUploadedFile ()
	{
		if ($this->_loadedFile != null)
		{
			Utils::deleteFile($this->_loadFile);	
		}
	}
	
	//if OK, return uploaded file name, if failed, return null;
	public function process ()
	{
		if(!$this->isUploadOK())
			return null;
		
		$uploadDir = $this->_uploadDir;
		if ($this->_isUnderDocRoot)
		{
 			$docRoot = ServerParams::getDocumentRoot ();	
 			Log::debug ('docRoot=' .$docRoot);
 			$uploadDir = $docRoot .$this->_uploadDir;
		}
		
 		if (false === Utils::endsWith ($uploadDir, "/"))
 			$uploadDir .= "/";
 		
 		Log::debug ('uploadDir:' .$uploadDir);
 		$fileName = $_FILES[$this->_uploadName]['name'];
 		$fileSize = $_FILES[$this->_uploadName]['size'];
    	$fileType = $_FILES[$this->_uploadName]['type'];
    	
    	$name = $_POST['name'];
    	
    	if ($name == null || $name == '')
    	{
     		$name = substr($fileName, 0, strripos($fileName, '.')); 
    	}
    	
    	if ($this->isImageFile ($fileType))
    	{
    		$fileParts = pathinfo($fileName);
    		$ext = $fileParts['extension']; 
    		$fname = substr($fileName, 0, strripos($fileName, '.')); 
    		$fileName = $fname ."." .strtolower ($ext);
    	}
    
    	//makeup the file name
    	$uploadedFile = $this->makeupFileName ($fileName);
    
    	$upload = new Upload(); 
    	$upload->setNewFileName($uploadedFile);
    	$upload->SetFileName($fileName); 
    	$upload->SetTempName($_FILES[$this->_uploadName]['tmp_name']); 
    	$upload->SetUploadDirectory($uploadDir); //Upload directory, this should be writable 
    	if ($this->_valieFileExt != nulL)
    		$upload->SetValidExtensions($this->_valieFileExt); //Extensions that are allowed if none are set all extensions will be allowed. 
    	//$upload->SetEmail("Sidewinder@codecall.net"); //If this is set, an email will be sent each time a file is uploaded. 
    	//$upload->SetIsImage(true); //If this is set to be true, you can make use of the MaximumWidth and MaximumHeight functions. 
    	//$upload->SetMaximumWidth(60); // Maximum width of images 
    	//$upload->SetMaximumHeight(400); //Maximum height of images 
    	$upload->SetMaximumFileSize($this->_maxFileSize); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value 
    	if ($upload->UploadFile())
    	{
    		//$uploadedFile = $upload->GetFileName();
    		Log::debug('upload succeeded:' .$uploadedFile);    	
    	
    		//save to database
    		$loadedFile = $upload->GetUploadDirectory()  .$uploadedFile; 
    		
    		$this->processFile ($name, $uploadedFile, $fileType, $fileSize, $loadedFile);
     		$this->_loadedFle = $loadedFile;
			return $uploadedFile;
    	}
    	
    	return null;		
	}
	
	protected function makeupFileName ($name)
	{
		if ($this->_namePrefix == null)
			return $name;
		else
		    return $this->_namePrefix .'_' .$name;
	}
	
	abstract protected function processFile ($name, $uploadfileName, $fileType, $fileSize, $fullFilePath);
	
	
	protected function isImageFile ($fileType)
	{
		if (Utils::startsWith($fileType, 'image/'))
			return true;
		else 
			return false;
		
	}
	
} 
?>