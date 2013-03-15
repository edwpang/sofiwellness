<?php
/*
 * $Id:$
 * FILE:MessageInfo.php
 * CREATE: Jul 22, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class MessageInfo
{
	const TYPE_TO = 0;
	const TYPE_CC = 1;	

	private $_id;
	private $_userId;   //user Id
	private $_sender=null;   //sender
	private $_createTime;
	private $_recipients; //string contains recipients, seperated by ;
	private $_recipientsCC = null; //string contains recipients - cc type
	private $_subject;
	private $_content;
	private $_attachment;
	private $_type = null;   //site, email
	private $_category; //message, post, mail
	private $_status;
	
	public function __construct ()
	{
		
	}
	
	public function setMessageId ($id)
	{
		$this->_id = $id;
	}
	
	public function getMessageId ()
	{
		return $this->_id;
	}
	
	public function setMessageUserId ($userId)
	{
		$this->_userId = $userId;
	}
	
	public function getMessageUserId ()
	{
		return $this->_userId;
	}
	
	public function setSender ($sender)
	{
		$this->_sender = $sender;
	}
	
	public function getSender ()
	{
		return $this->_sender;
	}
	
	public function setCreateTime ($createTime)
	{
		$this->_createTime = $createTime;
	}
	
	public function getCreateTime ()
	{
		if ($this->_createTime == null)
			$this->_createTime = Utils::getCurrentTime();
		return $this->_createTime;
	}
	
	public function setRecipients ($recipients, $type)
	{
		Log::debug('setRecipients:' .$recipients .', type:' .$type);
		if ($type == MessageInfo::TYPE_CC)
			$this->_recipientsCC = $recipients;
		else
			$this->_recipients = $recipients;
	}
	
	public function getRecipients ($type)
	{
		if ($type == MessageInfo::TYPE_CC)
			return $this->_recipientsCC;
		else
			return $this->_recipients;
	}

	
	public function getAllRecipients ()
	{
		Log::debug('###getAllRecipients:' .$this->_recipients);
		if (isset($this->_recipientsCC))
			return $this->_recipients .';' .$this->_recipientsCC;
		else
			return $this->_recipients;
	}
	
	public function setSubject ($subject)
	{
		$this->_subject = $subject;
	}
	
	public function getSubject ()
	{
		return $this->_subject;
	}
	
	public function setContent ($content)
	{
		$this->_content = $content;
	}
	
	public function getContent ()
	{
		return $this->_content;
	}
	
	public function setAttachment ($attach)
	{
		$this->_attachment = $attach;
	}
	
	public function getAttachment ()
	{
		return $this->_attachment;
	}
	
	public function setType ($type)
	{
		$this->_type = $type;
	}
	
	public function getType ()
	{
		return $this->_type;
	}
	
	public function setCategory ($category)
	{
		$this->_category = $category;
	}
	
	public function getCategory ()
	{
		return $this->_category;
	}
	
	public function setStatus ($status)
	{
		$this->_status = $status;
	}
	
	public function getStatus ()
	{
		return $this->_status;
	}
	
	public function toString ()
	{
		$str = '';
		
		
		return $str;
	}
}
?>