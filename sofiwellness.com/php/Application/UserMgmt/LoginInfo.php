<?php
/*
 * $Id: LoginInfo.php,v 1.3 2009/04/24 19:32:53 gorsen Exp $
 * FILE:LoginInfo.php
 * CREATE: Oct 2, 2008
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class LoginInfo 
{
	private $_id;
	private $_accountType = AccountTypes::MEMBER;
	private $_userName;
	private $_password;
	private $_question;
	private $_answer;
	private $_createTime = null;
	private $_lastVisit;
	private $_lastLogout;
	private $_status;
	
	public function setId ($id)
	{
		$this->_id = $id;	
	}
	
	public function getId ()
	{
		return $this->_id;
	}
	
	public function setAccountType ($type)
	{
		$this->_accountType = $type;	
	}
	
	public function getAccountType ()
	{
		return $this->_accountType;	
	}
	
	public function setUserName ($name)
	{
		$this->_userName = $name;
	}
	
	public function getUserName ()
	{
		return $this->_userName;
	}
	
	public function setPassword ($password)
	{
		$this->_password = $password;
	}
	
	public function getPassword ()
	{
		return $this->_password;
	}
	
	public function setQuestion ($question)
	{
		$this->_question = $question;
	}
	
	public function getQuestion ()
	{
		return $this->_question;
	}
	
	public function setAnswer ($anwser)
	{
		$this->_answer = $anwser;
	}
	
	public function getAnswer ()
	{
		return $this->_answer;
	}
	
	
	public function setCreateTime ($date)
	{
		$this->_createTime = $date;
	}

	public function getCreateTime ()
	{
		if ($this->_createTime == null)
			$this->_createTime = Utils::getCurrentTimeStr();
			
		return $this->_createTime;
	}

	public function setLastVisitTime ($val)
	{
		$this->_lastVisit = $val;
	}

	public function getLastVisitTime ()
	{
		return $this->_lastVisit;
	}
	
	public function setLastLogoutTime ($val)
	{
		$this->_lastLogout = $val;
	}

	public function getLastLogoutTime ()
	{
		return $this->_lastLogout;
	}
	
	public function setStatus ($status)
	{
		$this->_status = $status;
	}
	
	public function getStatus ()
	{
		return $this->_status;
	}
	
	public function copy ($info)
	{
		$this->_id = $info->getId();
		$this->_userName = $info->getUserName();
		$this->_password = $info->getPassword();
		$this->_question = $info->getQuestion();
		$this->_answer = $info->getAnswer();
		$this->_createTime = $info->getCreateTime();
		$this->_lastVisit = $info->getLastVisitTime();
		$this->_lastLogout = $info->getLastLogoutTime();
		$this->_status = $info->getStatus();
		
	}
		
}
?>