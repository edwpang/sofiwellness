<?php
/*
 * $Id:$
 * FILE:EmailSender.php
 * CREATE: Jul 22, 2010
 * BY:guosheng
 *  
 * NOTE:
 * 
 */
class EmailSender
{
	private $_errorMessage = null;
	

	
	//to is the email address such someone@somewhere.com
    public function send ($to, $msgInfo, $headers=null)
    {
    	Log::debug ('SendMessageHandler::sendEmail');
    	$this->_errorMessage = null;
    	
    	if ($headers != null)
    		Log::debug ('headers:' .$headers);
		$subject = $msgInfo->getSubject();
		$body = $msgInfo->getContent();
		$bOK = false;
		if ($headers != null)
			$bOK = mail($to, $subject, $body, $headers);
		else
			$bOK = 	mail($to, $subject, $body);

		if ($bOK)
		{
  			Log::error("Message successfully sent!");
 		}
 		else
 		{
  			Log::error("Message delivery failed");
  			$this->_errorMessage = 'Send email failed';
 		}

		return $bOK;
    }	
    
    public function createMessageInfo ($senderId, $senderName, $recipientNames, $subject, $content)
	{
		$info = new MessageInfo ();
		$info->setMessageUserId ($senderId);
		$info->setSender ($senderName);
		$info->setRecipients ($recipientNames, MessageInfo::TYPE_TO);
		$info->setSubject ($subject);
		$info->setContent ($content);
		$info->setCreateTime(Utils::getCurrentTime());

		return $info;
	}	
	
	public function makeEmailHeader ($senderName)
	{
		$headers = "From: " .$senderName ."@" .GlobalConstants::SITE_EMAIL ."\r\n";
		return $headers;
	}	
		
	public function getErrorMessage ()
	{
		return $this->_errorMessage;
	}
	
	
}
?>