<?php
/*
 * $Id: ImageUploadHandler.php,v 1.2 2009/10/04 00:07:06 gorsen Exp $
 * FILE:ImageUploadHandler.php
 * CREATE: Jul 8, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once 'FileUploadHandler.php';

class ImageUploadHandler extends FileUploadHandler
{
	private $_imageInfo = null;
	private $_fullPath = null;
	private $_imageLimitWidth = null;
	private $_imageLimitHeight = null;
	
	public function setImageInfo ($imageInfo)
	{
		$this->_imageInfo = $imageInfo;	
	}
	
	public function getImageInfo ()
	{
		return $this->_imageInfo;	
	}
	
	public function setImageLimitSize($width, $height)
	{
		$this->_imageLimitWidth = $width;
		$this->_imageLimitHeight = $height;	
	}
	
	public function getFileFullPath ()
	{
		return $this->_fullPath;	
	}
	
	protected function processFile ($name, $uploadfileName, $fileType, $fileSize, $fullFilePath)
	{
		Log::debug ('ImageUploadHandler::processFile:' .$fullFilePath .', type:' .$fileType);
	
		$path = $fullFilePath;
		$fileName = $uploadfileName;
		$this->_fullPath = $path;
		$imgSize = getimagesize ($path);
		//resize image if needed
		if ($this->_imageLimitWidth != null && $this->_imageLimitHeight != null)
		{
			if ($imgSize[0] > $this->_imageLimitWidth || $imgSize[1] > $this->_imageLimitHeight)
			{
				$imageWrapper = new ImageWrapper ();
				$imageWrapper->load ($path);	
				$imageWrapper->resizeKeepRatio($this->_imageLimitWidth, $this->_imageLimitHeight);
				//Log::debug ('resize:w=' .$imageWrapper->getWidth() .', h=' .$imageWrapper->getHeight());
				$imageWrapper->save ($path);
			}	
		}

		if ($this->_imageInfo == null)
			$this->_imageInfo = new ImageInfo();
			
		$this->_imageInfo->setName($name);
		$this->_imageInfo->setSize($fileSize);	
		$this->_imageInfo->setFile($fileName);
		$this->_imageInfo->setImageType($fileType);
				
		$imgSize = getimagesize ($path);
		if ($imgSize)
		{
			$this->_imageInfo->setWidth($imgSize[0]);
			$this->_imageInfo->setHeight ($imgSize[1]);	
			Log::debug('image: width=' .$this->_imageInfo->getWidth() .', h=' .$this->_imageInfo->getHeight());	
		}
	}	
	
}
?>