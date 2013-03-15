<?php
/*
 * Created on Sep 14, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


class DbAccessor
{	
	protected $_pageSize = 0;
	protected $_pageNum = 1;
	protected $_orderBy = null;
	protected $_errorMessage = null;
		
	public function setPageSize ($size)
	{
		$this->_pageSize = $size;
	}
	
	public function getPageSize ()
	{
		return $this->_pageSize;
	}
	
	public function setPageNum ($num)
	{
		$this->_pageNum = $num;
	}
	
	public function getPageNum ()
	{
		return $this->_pageNum;
	}
	
	public function setOrderBy ($orderBy)
	{
		$this->_orderBy = $orderBy;
	}
	
	public function getOrderBy ()
	{
		return $this->_orderBy;
	}
	
	public function setErrorMessage ($msg)
	{
		$this->_errorMessage = $msg;
	}
	
	public function getErrorMessage ()
	{
		return $this->_errorMessage;
	}
	
	/** get database connection with given name.
	 * default to AuthDb. 
	 * 
	 * TODO may need to differentiate name as null and incorrect name.
	 */
	public function getDatabase ($name=null)
	{
		return ZendHelper::getAppDb();
	}
	
	

	//////////////////////////////////////////
	//protected
	protected function getPageOffset ()
	{
		if ($this->_pageNum <= 0)
			$this->_pageNum = 1;
		$offset = ($this->_pageNum - 1) * $this->_pageSize;
		return $offset;
	}
	
	protected function getLimitSql ()
	{
		$sql = null;
		if ($this->getPageSize() > 0)
		{
			$pageSize = $this->getPageSize();
			$pageNum = $this->getPageNum();
			if ($pageNum <= 0)
				$pageNum = 1;
			$offset = ($pageNum - 1) * $pageSize;
         	$sql .=" LIMIT " .$offset ."," .$pageSize;
		}
		
		return $sql;
	}
	
	//////////////////////////////////////////////
	//private

} 
 
?>
