<?php
/*
 * $Id:$
 * FILE:ServiceTimeDbAccessor.php
 * CREATE: May 31, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class ServiceTimeDbAccessor extends BaseDbAccessor
{
	const TABLE_SERVICETIME = 'appointment_service_time';


	public function _construct ()
	{

	}
	
	public function getServiceTimeInfoListByDate ($userId, $date)
	{
		$list = null;
		$table = ServiceTimeDbAccessor::TABLE_SERVICETIME;
		$sql = "SELECT * FROM " .$table ." WHERE user_id=" .$userId;
		if ($date != null)
			$sql .= " AND the_date='" .$date ."'";
		$orderBy = $this->getOrderBy ();
		if ($orderBy == null)
			$orderBy = 'ORDER BY the_date';
		$sql.= ' ' .$orderBy;
			
		$offset = $this->getPageOffset();
		$pageSize = $this->getPageSize();
		if ($pageSize != 0)
			$sql .=" LIMIT " .$offset ."," .$pageSize;
				
		Log::debug ('SQL:' .$sql);
			
		$list = parent::get ($sql, array($this, 'retrieveServiceTimeInfoListCB'));
		if ($list == null)
			$list = new ArrayList();
		return $list;
	}
	
	
	public function getServiceTimeInfoById ($id)
	{
		$info = null;
		if ($id == null)
			return $info;
			
		$db = $this->getDb();
		try
		{
			$table = ServiceTimeDbAccessor::TABLE_SERVICETIME;
			$sql = "SELECT * FROM " .$table ." WHERE id=" .$id;
				
			Log::debug ('SQL:' .$sql);
			$info = parent::get($sql, array($this, 'retrieveServiceTimeInfoCB'));			
		}
		catch (Exception $e)
		{
			Log::error ('getServiceTimeInfoById error:' .$e->getMessage());
			$this->setErrorMessage ('getServiceTimeInfoById error:' .$e->getMessage());	
		}	
				
		return $info;
	}
	
	public function getServiceTimeInfoListByType ($userId, $typeName=null)
	{
		$list = null;
		$db = $this->getDb();
		try
		{
			$table = ServiceTimeDbAccessor::TABLE_SERVICETIME;
			$sql = "SELECT * FROM " .$table ." WHERE user_id=" .$userId;
			if ($typeName != null)
				$sql .= " AND type_name='" .$typeName ."'";
				
			//Log::debug ('SQL:' .$sql);
			$list = parent::get($sql, array($this, 'retrieveServiceTimeInfoListCB'));			
		}
		catch (Exception $e)
		{
			Log::error ('getServiceTimeInfoByNote error:' .$e->getMessage());
			$this->setErrorMessage ('Error:' .$e->getMessage());	
		}	
				
		return $list;
	}
	
	public function getServiceTimeInfoByDateType ($userId, $theDate, $onDuty=1, $type=null)
	{
		$info = null;
		$list = $this->getServiceTimeInfoListBy ($userId, $theDate, $onDuty, $type);
		if ($list != null && $list->size () > 0)
			$info = $list->get(0);
		return $info;
	}
	
	
	public function getServiceTimeInfoListBy ($userId, $theDate, $onDuty=1, $type=null)
	{
		$list = null;
		$db = $this->getDb();
		try
		{
			$table = ServiceTimeDbAccessor::TABLE_SERVICETIME;
			$sql = "SELECT * FROM " .$table ." WHERE user_id=" .$userId;
			if ($theDate != null)
				$sql .= " AND the_date='" .$theDate ."'";
			if ($type != null)
				$sql .= " AND type_name='" .$type ."'";
			if ($onDuty != null)
				$sql .= " AND on_duty=" .$onDuty;
				
			Log::debug ('SQL:' .$sql);
			$list = parent::get($sql, array($this, 'retrieveServiceTimeInfoListCB'));			
		}
		catch (Exception $e)
		{
			Log::error ('getServiceTimeInfoByDateNote error:' .$e->getMessage());
			$this->setErrorMessage ('Error:' .$e->getMessage());	
		}	
				
		return $list;
	}
	
	//return those user_id != 0 specify $resId = null
	public function getServiceTimeListByResType($theDate, $resId, $onDuty=1, $type=null)
	{
		$list = null;
		$db = $this->getDb();
		try
		{
			$table = ServiceTimeDbAccessor::TABLE_SERVICETIME;
			$sql = "SELECT * FROM " .$table ." WHERE the_date='".$theDate ."'";
			if ($resId != null)
				$sql .= " AND user_id=" .$resId;
			else
				$sql .= " AND user_id != 0";
			$sql .= " AND on_duty=" .$onDuty;
			if ($type != null)
				$sql .= " AND type_name='" .$type ."'";
				
			Log::debug ('SQL:' .$sql);
			$list = parent::get($sql, array($this, 'retrieveServiceTimeInfoListCB'));			
		}
		catch (Exception $e)
		{
			Log::error ('getResourceServiceTimeListByDateNote error:' .$e->getMessage());
			$this->setErrorMessage ('Error:' .$e->getMessage());	
		}	
				
		return $list;		
	}
	
	//return resource available for the date in general
		
	public function saveServiceTimeInfo ($info)
	{
		$db = $this->getDb();
		try
		{
			$table = ServiceTimeDbAccessor::TABLE_SERVICETIME;
			$params = $this->binServiceTimeInfo ($info);
			Log::debug ('save:userId=' .$info->getUserId);
			$id = parent::save ($table, $params);
			$info->setId ($id);
			
		}
		catch (Exception $e)
		{
			Log::error ('saveServiceTimeInfo error:' .$e->getMessage());
			$this->setErrorMessage ('saveServiceTimeInfo error:' .$e->getMessage());	
		}	
		
		return $info;
	}
	
	
	public function updateServiceTimeInfo ($info, $db=null)
	{
		if ($db == null)
			$db = $this->getDb();
		$table = ServiceTimeDbAccessor::TABLE_SERVICETIME;
		$params = $this->binServiceTimeInfo ($info);
		parent::update ($table, $info->getId(), $params);
		
		return $info;
	}	
	
	public function deleteServiceTimeInfo ($id, $db=null)
	{
		if ($db == null)
			$db = $this->getDb();
			
		$table = ServiceTimeDbAccessor::TABLE_SERVICETIME;
		return parent::delete ($table, $id);
	}
	
	public function deleteServiceTimeByUserId ($userId)
	{
		$ret = false;
		$table = ServiceTimeDbAccessor::TABLE_SERVICETIME;
		$db = $this->getDb();
		try
		{
			// the WHERE clause
			$where = $db->quoteInto('user_id = ?', $userId);
			$rows_affected = $db->delete($table, $where);
			$ret = true;
		}
		catch (Exception $e)
		{
			Log::error ($e->getMessage());
			$this->setErrorMessage ('Delete error:' .$e->getMessage());
		}
		
		return $ret;
	}
	
	//callback function ////////////////////////////////
	public function retrieveServiceTimeInfoListCB ($rows)
	{
		$ret = new ArrayList ();
		foreach ($rows as $row)
		{
			$info = new ServiceTimeInfo ();
			$this->populateServiceTimeInfo ($row, $info);
			$ret->add ($info);
		}
		return $ret;	
	}
	
	//retrieve single data, name as gameData
	public function retrieveServiceTimeInfoCB ($rows)
	{
		$ret = null;
		foreach ($rows as $row)
		{
			$ret = new ServiceTimeInfo();
			$this->populateServiceTimeInfo ($row, $ret);
		}
		return $ret;
	}
	
	
	////////////////////////////////////////////////
	//
	protected function getDb ()
	{
		return parent::getDatabase ();	
	}
	
	
	private function binServiceTimeInfo ($info)
	{
		$param = array (
			'user_id' => $info->getUserId (),
			'the_date' => $info->getTheDate(),
			'on_duty' => $info->getOnDuty(),
			'start_time' => $info->getStartTime(),
			'end_time' => $info->getEndTime(),
			'type_name' => $info->getTypeName(),
			'note' => $info->getNote(),
			'update_time' => $info->getUpdateTime ()
		);	
			
		return $param;
	}
	
	
	private function populateServiceTimeInfo ($row, $info)
	{
		$info->setId ($row['id']);	
		$info->setUserId ($row['user_id']);
		$info->setOnDuty ($row['on_duty']);
		$info->setTheDate ($row['the_date']);
		$info->setStartTime ($row['start_time']);
		$info->setEndTime ($row['end_time']);
		$info->setTypeName ($row['type_name']);
		$info->setNote ($row['note']);
		$info->setUpdateTime ($row['update_time']);
		
		return $info;
	}
}
?>