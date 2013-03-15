<?php
/*
 * $Id:$
 * FILE:ServiceItemDbAccessor.php
 * CREATE: Jun 21, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class ServiceItemDbAccessor extends BaseDbAccessor
{
	const TABLE_SERVICEITEM = 'service_item';

	public function _construct ()
	{

	}
	
	public function save ($info)
	{
		$db = $this->getDb();
		$table = ServiceItemDbAccessor::TABLE_SERVICEITEM;
		$params = $this->binServiceItemInfo ($info);
		$id = parent::save ($table, $params);
		$info->setId ($id);
		return $info;
	}
	
	public function updateServiceItemInfo ($info, $db=null)
	{
		if ($db == null)
			$db = $this->getDb();
		$table = ServiceItemDbAccessor::TABLE_SERVICEITEM;
		$params = $this->binServiceItemInfo ($info);
		parent::update ($table, $info->getId(), $params);
		
		return $info;
	}	
	
	public function deleteServiceItemInfo ($id, $db=null)
	{
		if ($db == null)
			$db = $this->getDb();
			
		$table = ServiceItemDbAccessor::TABLE_SERVICEITEM;
		return parent::delete ($table, $id);
	}
	
	//return list of ServiceItem
	public function getServiceItemsByLanguage ($language)
	{
		$list = null;
		$table = ServiceItemDbAccessor::TABLE_SERVICEITEM;
		$sql = "SELECT * FROM " .$table ." WHERE language='".$language ."'";
		$orderBy = $this->getOrderBy ();
		if ($orderBy == null)
			$orderBy = 'ORDER BY list_order, create_time';
		$sql.= ' ' .$orderBy;
			
		$offset = $this->getPageOffset();
		$pageSize = $this->getPageSize();
		if ($pageSize != 0)
			$sql .=" LIMIT " .$offset ."," .$pageSize;
				
		Log::debug ('SQL:' .$sql);
			
		$list = parent::get ($sql, array($this, 'retrieveServiceItemInfoListCB'));
		if ($list == null)
			$list = new ArrayList();
		return $list;		
	}
	
	public function getServiceItemInfoById ($id)
	{
		$info = null;
		if ($id == null)
			return $info;
			
		$db = $this->getDb();
		$table = ServiceItemDbAccessor::TABLE_SERVICEITEM;
		$sql = "SELECT * FROM " .$table ." WHERE id=" .$id;
				
		Log::debug ('SQL:' .$sql);
		$info = parent::get($sql, array($this, 'retrieveServiceItemInfoCB'));			
				
		return $info;
	}
	
	
	//callback function ////////////////////////////////
	public function retrieveServiceItemInfoListCB ($rows)
	{
		$ret = new ArrayList ();
		foreach ($rows as $row)
		{
			$info = new ServiceItemInfo ();
			$this->populateServiceItemInfo ($row, $info);
			$ret->add ($info);
		}
		return $ret;	
	}
	
	//retrieve single data, name as gameData
	public function retrieveServiceItemInfoCB ($rows)
	{
		$ret = null;
		foreach ($rows as $row)
		{
			$ret = new ServiceItemInfo();
			$this->populateServiceItemInfo ($row, $ret);
		}
		return $ret;
	}
	
	
	//////////////////////////////////////////////////////////
	protected function getDb ()
	{
		return parent::getDatabase ();	
	}
	
	
	private function binServiceItemInfo ($info)
	{
		$param = array (
			'name' => $info->getName(),
			'image_id' => $info->getImageId (),
			'image_ref' => $info->getImageRef(),
			'title' => $info->getTitle(),
			'language' => $info->getLanguage(),
			'description' => $info->getDescription(),
			'list_order' => $info->getListOrder(),
			'status' => $info->getStatus(),
			'create_time' => $info->getCreateTime()
		);	
			
		return $param;
	}
	
	
	private function populateServiceItemInfo ($row, $info)
	{
		$info->setId ($row['id']);	
		$info->setName ($row['name']);
		$info->setImageId ($row['image_id']);
		$info->setImageRef ($row['image_ref']);
		$info->setTitle ($row['title']);
		$info->setLanguage ($row['language']);
		$info->setDescription ($row['description']);
		$info->setListOrder ($row['list_order']);
		$info->setStatus ($row['status']);
		$info->setCreateTime ($row['create_time']);
		return $info;
	}
}

?>
