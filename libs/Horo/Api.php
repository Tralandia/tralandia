<?php

namespace Horo;


class Api {
	private $host;
	private $username;
	private $password;
	private $folder;
	private $port;
	private $tls;		
	private $con;
	private $maxEmails = false;
	
	function __construct($host, $username, $password, $folder = 'INBOX', $port = 143, $tls = 'notls') {
		$this->con = new Imap($host, $username, $password, $folder, $port, $tls);
	}
	
	public function setMaxEmails($max = false) {
		if($max) {
			$this->maxEmails = $max;					
		}
	}
	
	public function getMaxEmails() {
		return $this->maxEmails;
	}
	
	/**
	* @param $filter 	@see Api->getMessageIds()
	*/
	public function getMessages($filter = 'unseen', $type = '', $attachment = false) {
		$result = false;
		
		$filter = array('filter' => $filter, 'type' => $type);
		$msgIds = $this->con->getMessageIds($filter);
		
		if(is_array($msgIds)) {
			$i = 0;
			
			$result['totalEmails'] = count($msgIds);
			foreach ($msgIds as $id) {
				if( ($i < $this->maxEmails) ) {
					$result['emails'][] = $this->con->getMessage($id, $attachment);
					$i++;
				}
			}
			
		}			
		
		return $result;
	}

	public function getMessage($messageId, $attachment = false) {
		return $this->con->getMessage($messageId, $attachment);	
	}

	public function setMove($id = false, $label = false) {
		if($id && $label) {
			return $this->con->move($id, $label);
		} else {
			return false;				
		}						
	}
}