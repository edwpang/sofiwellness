<?php
/*
 * $Id: BaseDbAccessor.php,v 1.2 2010/01/11 15:26:29 gorsen Exp $
 * FILE:BaseDbAccessor.php
 * CREATE: Aug 26, 2009
 * BY:guosheng
 *  
 * NOTE:
 * 
 */

require_once 'DbAccessor.php';

abstract class BaseDbAccessor extends DbAccessor
{		
	protected $_retrieveFieldName = null;
	
	abstract protected function getDb ();

	//callbackFunc is  callback function
	//If you pass an array(obj, methodname) as first parameter it will invoked as $obj->methodname().
	/*sample:
	class Foo {
	    public function bar($x) {
	    	echo $x;
	    }
	}

	function xyz($cb) {
    $value = rand(1,100);
    call_user_func($cb, $value);
	}

	$foo = new Foo;
	xyz( array($foo, 'bar') );
	*/
	public function get ($sql, $callbackFunc, $db=null)
	{
		$ret = null;
		try
		{
			if ($db == null)
				$db = $this->getDb();
			if ($db == null)
				Log::debug ('###db = null');
			$result = $db->query($sql);
			$rows = $result->fetchAll();
			$ret = call_user_func($callbackFunc, $rows);
		}	
		catch (Exception $e)
		{
			Log::debug ('SQL:' .$sql);
			Log::debug ('get:' .$e->getMessage());	
			$this->setErrorMessage ($e->getMessage());		
		}				
		
		return $ret;
	}
	
	//the callbackFunc just for populating the info, then will be put into list and return
	public function getList ($sql, $callbackFunc, $db=null)
	{
		$ret = null;
		try
		{
			if ($db == null)
				$db = $this->getDb();

			$result = $db->query($sql);
			$rows = $result->fetchAll();
			$ret = new ArrayList();
			foreach ($rows as $row)
			{
				$info = call_user_func($callbackFunc, $row);
				$ret.add ($info);
			}
		}	
		catch (Exception $e)
		{
			Log::debug ('SQL:' .$sql);
			Log::debug ('get:' .$e->getMessage());	
			$this->setErrorMessage ($e->getMessage());		
		}				
		
		return $ret;
	}
	
	//return id
	public function save ($table, $params, $db=null)
	{
		$insertId = null;
		try 
		{  	
			if ($db == null)
				$db = $this->getDb ();
			$rows_affected = $db->insert($table, $params);
			$insertId = $db->lastInsertId();	
			
		} 
		catch (Exception $e) 
		{    			
			Log::debug ('save error:' .$e->getMessage());
			$this->setErrorMessage ($e->getMessage());
		}	
		
		return $insertId;		
	}	
	
	public function update ($table, $id, $params, $db=null)
	{
		if ($db == null)
			$db = $this->getDb();
			
		$ret = false;
		try
		{
			$where = $db->quoteInto('id=?', $id);
			$rows_affected = $db->update($table, $params, $where);
			$ret = true;
		}
		catch (Exception $e)
		{
			Log::error ('update error:' .$e->getMessage());
			$this->setErrorMessage ($e->getMessage());
		}

		return $ret;
	}
	
	//return true or false if failed
	public function delete ($table, $id, $db=null)
	{
		if ($db == null)
			$db = $this->getDb();
			
		$ret = false;
		try
		{
			// the WHERE clause
			$where = $db->quoteInto('id = ?', $id);
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
	
	///////////////common callback/////////////////
	//you need to specify the field name
	public function setRetrieveFieldName($name)
	{
		$this->_retrieveFieldName = $name;	
	}
	
	public function retrieveFieldDataCB ($rows)
	{
		$ret = null;
		foreach ($rows as $row)
		{
			$ret = $row[$this->_retrieveFieldName];
		}
		return $ret;
	}

}
?>