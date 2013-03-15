<?php
/*
 * $Id: ImageInfoDbAccessor.php,v 1.1 2009/10/04 00:06:06 gorsen Exp $
 * FILE:ImageInfoDbAccessor.php
 * CREATE: Aug 26, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
require_once APP_COMMON_DIR .'/Models/ImageInfo.php';

class ImageInfoDbAccessor extends BaseDbAccessor 
{
	const TABLE_IMAGEINFO = 'image_info';
	
	private $_table = ImageInfoDbAccessor::TABLE_IMAGEINFO;
	private $_dbName = 'image';
	
	public function setImageDbName ($dbName)
	{
		$this->_dbName = $dbName;	
	}
	
	public function setImageTable ($tableName)
	{
		$this->_table = $tableName;
	}
	
	public function saveImageInfo ($info)
	{
		Log::debug ('ImageInfoDbAccessor::saveImageInfo');
		$params = $this->binImageInfoParams ($info);
		$table = $this->_table;
		$id = parent::save ($table, $params);
		$info->setId ($id);
		return $info;
	}
	
	public function updateImageInfo ($info)
	{
		Log::debug ('ImageInfoDbAccessor::updateImageInfo');
		$table = $this->_table;			
		$params = $this->binImageInfoParams($info);
		return parent::update ($table, $info->getId(), $params);		
	}
	
	public function getImageInfo ($id)
	{
		$table = $this->_table;			
		$sql = "SELECT * FROM " .$table;
		$sql .= " WHERE id=" .$id;		
		$info = parent::get ($sql, array($this, 'retrieveImageInfo'));	
		return $info;	
	}
	
	//arrayAttr contains: key => value for where
	public function getImageInfoBy($arrayAttr)
	{
		$table = $this->_table;			
		$sql = "SELECT * FROM " .$table;		
		if ($arrayAttr != null &&  count ($arrayAttr)> 0)
		{
			$sql .= " WHERE ";
			$addAnd = false;
			foreach ($arrayAttr as $key => $val)
			{
				if ($addAnd)
					$sql .= " AND ";
				$sql .= $key ."='".$val ."'";
				if ($addAnd == false)
					$addAnd = true;
			}
		}
		Log::debug ('SQL:' .$sql);
		$info = parent::get ($sql, array($this, 'retrieveImageInfo'));		
		return $info;
	}

	public function getImageInfoByUserAndName ($userName, $file)
	{
		$table = $this->_table;
		$sql = "SELECT * FROM " .$table;
		$sql .= " WHERE user_name='" .$userName ."' AND image_file='".$file ."'";
		$info = parent::get($sql, array($this, 'retrieveImageInfo'));	
		return $info;
	}
	
	public function getImageInfoByUserId ($userId)
	{
		$table = $this->_table;
		$sql = "SELECT * FROM " .$table;
		$sql .= " WHERE user_id='" .$userId ."'";
		$info = parent::get($sql, array($this, 'retrieveImageInfo'));	
		return $info;
	}
	
	
	//listImageIds is ArrayList
	public function getImageListByIds ($listImageIds)
	{
		$table = $this->_table;			
		$list = new ArrayList();
		if ($listImageIds == null || 0 == $listImageIds->size())
			return $list;

		$sql = 'SELECT *  FROM ' .$table .' where id in (';
		$count = $listImageIds->size();
		for ($i = 0; $i < $count; $i++)
		{
			if ($i > 0)
				$sql .= ",";
			$sql .= $listImageIds->get ($i);
		}
		$sql .= ")";

		$list = parent::get ($sql, array($this, 'retrieveImageInfoList'));		
		return $list;
	}
	
	
	////return ArrayList contains ImageInfo
	public function getImageByUserIdType ($userId, $typeName, $category=null)
	{
		$table = $this->_table;			
		$sql = "SELECT * FROM " .$table;
		$sql .= " WHERE user_id=" .$userId;
		if ($typeName != null)
			$sql .= " AND type_name='" .$typeName ."'";
		if ($category != null)
			$sql .= " AND category='" .$category."'";		
		$list = parent::get ($sql, array($this, 'retrieveImageInfoList'));		
		return $list;
	}

	////return ArrayList contains ImageInfo
	public function getImageByUserNameType ($userName, $typeName, $category=null, $file=null)
	{
		$table = $this->_table;			
		$sql = "SELECT * FROM " .$table;
		$sql .= " WHERE user_name='" .$userName ."'";
		if ($typeName != null)
			$sql .= " AND type_name='" .$typeName ."'";
		if ($category != null)
			$sql .= " AND category='" .$category."'";
		if ($file != null)
			$sql .= " AND image_file='" .$file ."'";		
		$list = parent::get ($sql, array($this, 'retrieveImageInfoList'));		
		return $list;
	}
	

	
	//return true or false if failed
	public function deleteImageInfo ($id)
	{
		$table = $this->_table;			
		return parent::delete ($table, $id);	
	}

	
	/// callback functions
	public function retrieveImageInfo ($rows)
	{
		$info = null;
		foreach ($rows as $row)
		{
			$info = new ImageInfo();
			$this->populateImageInfo ($row, $info);
		}
		return $info;			
	}
	
	public function retrieveImageInfoList ($rows)
	{
		$list = new ArrayList ();
		foreach ($rows as $row)	
		{
			$info = new ImageInfo ();
			$this->populateImageInfo ($row, $info);
			$list->add ($info);	
		}
		return $list;
	}
	
	////////////////////////////////////
	//private
	protected function getDb ()
	{
		return parent::getDatabase ($this->_dbName);	
	}
	
	private function binImageInfoParams (ImageInfo $imageInfo)
	{
		$dataEncoded = null;
		if ($imageInfo->getData() != null)
			$dataEncoded = $imageInfo->encodeData ($imageInfo->getData());
		
		$updateTime = $imageInfo->getUpdateTime();
		if ($updateTime == null)
			$updateTime = DatetimeUtil::getCurrentTimeStr();
			
		$row = array (
			'name' => $imageInfo->getName(),
			'type_name' => $imageInfo->getTypeName(),
			'category' => $imageInfo->getCategory(),
			'ref' => $imageInfo->getRef (),
			'user_id' => $imageInfo->getUserId(),
			'user_name' => $imageInfo->getUserName(),
			'description' => $imageInfo->getDescription(),
			'tag' => $imageInfo->getTag (),
			'update_time'=> $updateTime,
			'image_data' => $dataEncoded,
			'image_type' => $imageInfo->getImageType(),
			'image_file' => $imageInfo->getImageFile(),
			'image_size' => $imageInfo->getImageSize(),
			'image_width' => $imageInfo->getImageWidth(),
			'image_height' => $imageInfo->getImageHeight(),
		);

		return $row;
	}

	private function populateImageInfo ($row, $image, $incData=true)
	{
		$image->setImageType($row['image_type']);
		if ($incData)
		{
			$data = $row['image_data'];
			//Log::debug ('getImageData:' .$data);
			$dataDecoded = null;
			if ($data != null)
			{
				$dataDecoded = $image->decodeData ($data);
			}
			$image->setData ($dataDecoded);
		}
		$image->setId ($row['id']);
		$image->setName($row['name']);
		$image->setTypeName($row['type_name']);
		$image->setCategory ($row['category']);
		$image->setRef ($row['ref']);
		$image->setUserId($row['user_id']);
		$image->setDescription($row['description']);
		$image->setTag ($row['tag']);
		$image->setUpdateTime($row['update_time']);
		$image->setImageFile($row['image_file']);
		$image->setImageSize($row['image_size']);
		$image->setImageWidth ($row['image_width']);
		$image->setImageHeight($row['image_height']);
		return $image;
	}
}
?>