<?php
/*
 * $Id: ReminderInfoDbAccessor.php,v 1.1 2009/02/26 19:56:10 gorsen Exp $
 * FILE:ReminderInfoDbAccessor.php
 * CREATE: Feb 17, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class AppointmentInfoDbAccessor extends BaseDbAccessor
{
	const TABLE_APPOINTMENTINFO = 'appointment_info';


	public function _construct ()
	{

	}
	
	public function getAppointmentInfoList ($userId, $fromDate, $toDate)
	{
		$list = null;
		$table = AppointmentInfoDbAccessor::TABLE_APPOINTMENTINFO;
		$sql = "SELECT * FROM " .$table ." WHERE user_id=" .$userId;
		if ($fromDate != null)
			$sql .= " AND start_time >'" .$fromDate ."'";
		if ($toDate != null)
			$sql .= " AND end_time <'" .$toDate ."'";
				
		$orderBy = $this->getOrderBy ();
		if ($orderBy == null)
			$orderBy = 'ORDER BY start_time';
		$sql.= ' ' .$orderBy;
			
		$offset = $this->getPageOffset();
		$pageSize = $this->getPageSize();
		if ($pageSize != 0)
			$sql .=" LIMIT " .$offset ."," .$pageSize;
				
		//Log::debug ('SQL:' .$sql);
			
		$list = parent::get ($sql, array($this, 'retrieveAppointmentListCB'));
		if ($list == null)
			$list = new ArrayList();
		return $list;
	}
	
	
	public function getAppointmentInfoById ($id)
	{
		$info = null;
		if ($id == null)
			return $info;
			
		$db = $this->getDb();
		try
		{
			$table = AppointmentInfoDbAccessor::TABLE_APPOINTMENTINFO;
			$sql = "SELECT * FROM " .$table ." WHERE id=" .$id;
				
			//Log::debug ('SQL:' .$sql);
			$info = parent::get($sql, array($this, 'retrieveAppointmentInfoCB'));			
		}
		catch (Exception $e)
		{
			Log::error ('getAppointmentInfoById error:' .$e->getMessage());
			$this->setErrorMessage ('getAppointmentInfoById error:' .$e->getMessage());	
		}	
				
		return $info;
	}
	
	public function getAppiontmentsByUser ($userId, $firstName, $lastName)
	{
		$table = AppointmentInfoDbAccessor::TABLE_APPOINTMENTINFO;
		$sql = "SELECT * FROM " .$table ." WHERE ";
		if ($userId != null)
			$sql .= ' user_id=' .$userId;
		
		if ($firstName != null && $lastName != null)
		{
			if ($userId != null)
				$sql .= " OR ";
			$sql .= " (first_name='". $firstName ."'";
			$sql .= " AND last_name='" .$lastName ."')";
		}
		
		$orderBy = $this->getOrderBy ();
		if ($orderBy == null)
			$orderBy = 'ORDER BY start_time';
		$sql.= ' ' .$orderBy;
			
		$offset = $this->getPageOffset();
		$pageSize = $this->getPageSize();
		if ($pageSize != 0)
			$sql .=" LIMIT " .$offset ."," .$pageSize;
				
		//Log::debug ('SQL:' .$sql);
			
		$list = parent::get ($sql, array($this, 'retrieveAppointmentListCB'));
		
		return $list;		
	}
	
	
	public function getAppointmentListByResourceId ($resourceId, $date, $timeFrom=null, $timeTo=null)
	{
		$list = null;
		$table = AppointmentInfoDbAccessor::TABLE_APPOINTMENTINFO;
		$sql = "SELECT * FROM " .$table ." WHERE resource_id='".$resourceId ."'";
		$sql .= " AND the_date='".$date ."'";
		if ($timeFrom != null)
			$sql .= " AND start_time >'" .$timeFrom ."'";
		if ($timeTo != null)
			$sql .= " AND end_time <'" .$timeTo ."'";
				
		$orderBy = $this->getOrderBy ();
		if ($orderBy == null)
			$orderBy = 'ORDER BY start_time';
		$sql.= ' ' .$orderBy;
			
		$offset = $this->getPageOffset();
		$pageSize = $this->getPageSize();
		if ($pageSize != 0)
			$sql .=" LIMIT " .$offset ."," .$pageSize;
				
		//Log::debug ('SQL:' .$sql);
			
		$list = parent::get ($sql, array($this, 'retrieveAppointmentListCB'));
		
		return $list;		
	}
	
	public function checkAvailable ($resourceId, $date, $fromTime, $toTime, $userId=null)
	{
		$list = null;
		$table = AppointmentInfoDbAccessor::TABLE_APPOINTMENTINFO;
		$sql = "SELECT * FROM " .$table ." WHERE resource_id='".$resourceId ."'";
		$sql .= " AND the_date='".$date."'";
		$sql .= " AND (end_time >'" .$fromTime ."'";
		$sql .= " AND start_time <'" .$toTime ."')";
		$sql .= " AND (status_code<2)";
		if ($userId != null)
			$sql .= " AND user_id !=" .$userId;
		//Log::debug ('SQL:' .$sql);
			
		$list = parent::get ($sql, array($this, 'retrieveAppointmentListCB'));
		if ($list != null && $list->size() > 0)
			return false;
		else
			return true;
	}
	
	public function saveAppointmentInfo ($info)
	{
		$db = $this->getDb();
		try
		{
			$table = AppointmentInfoDbAccessor::TABLE_APPOINTMENTINFO;
			$params = $this->binAppointmentInfo ($info);
			$id = parent::save ($table, $params);
			$info->setId ($id);
			
		}
		catch (Exception $e)
		{
			Log::error ('saveAppointmentInfo error:' .$e->getMessage());
			$this->setErrorMessage ('saveAppointmentInfo error:' .$e->getMessage());	
		}	
		
		return $info;
	}
	
	public function updateAppointmentInfo ($info, $db=null)
	{
		if ($db == null)
			$db = $this->getDb();
		$table = AppointmentInfoDbAccessor::TABLE_APPOINTMENTINFO;
		$params = $this->binAppointmentInfo ($info);
		parent::update ($table, $info->getId(), $params);
		
		return $info;
	}	
	
	public function updateAppointmentStatus ($id, $statusCode)
	{
		$db = $this->getDb();
		$table = AppointmentInfoDbAccessor::TABLE_APPOINTMENTINFO;
		$params = array('status_code' => $statusCode);
		return parent::update ($table, $id, $params);
	}
	
	public function deleteAppointmentInfo ($id, $db=null)
	{
		if ($db == null)
			$db = $this->getDb();
			
		$table = AppointmentInfoDbAccessor::TABLE_APPOINTMENTINFO;
		return parent::delete ($table, $id);
	}
	
	//callback function ////////////////////////////////
	public function retrieveAppointmentListCB ($rows)
	{
		$ret = new ArrayList ();
		foreach ($rows as $row)
		{
			$info = new AppointmentInfo ();
			$this->populateAppointmentInfo ($row, $info);
			$ret->add ($info);
		}
		return $ret;	
	}
	
	//retrieve single data, name as gameData
	public function retrieveAppointmentInfoCB ($rows)
	{
		$ret = null;
		foreach ($rows as $row)
		{
			$ret = new AppointmentInfo();
			$this->populateAppointmentInfo ($row, $ret);
		}
		return $ret;
	}
	
	
	//////////////////////////////////////////////////////////////
	//
	protected function getDb ()
	{
		return parent::getDatabase ();	
	}
	
	private function binAppointmentInfo ($info)
	{
		$param = array (
			'user_id' => $info->getUserId (),
			'first_name' => $info->getFirstName(),
			'last_name' => $info->getLastName(),
			'type_name' => $info->getTypeName(),
			'resource_id' => $info->getResourceId (),
			'subject' => $info->getSubject(),
			'detail' => $info->getDetail (),
			'the_date' => $info->getTheDate(),
			'start_time' => $info->getStartTime(),
			'end_time' => $info->getEndTime(),
			'time_length' => $info->getTimeLength(),
			'status_code' => $info->getStatus (),
			'create_time' => $info->getCreateTime ()
		);	
			
		return $param;
	}
	
	
	private function populateAppointmentInfo ($row, $info)
	{
		$info->setId ($row['id']);	
		$info->setUserId ($row['user_id']);
		$info->setFirstName ($row['first_name']);
		$info->setLastName ($row['last_name']);
		$info->setSubject($row['subject']);
		$info->setDetail($row['detail']);
		$info->setTheDate ($row['the_date']);
		$info->setStartTime ($row['start_time']);
		$info->setEndTime ($row['end_time']);
		$info->setTimeLength($row['time_length']);
		$info->setTypeName($row['type_name']);
		$info->setResourceId ($row['resource_id']);
		$info->setStatus ($row['status_code']);
		$info->setCreateTime ($row['create_time']);
		
		return $info;
	}
	
}

?>