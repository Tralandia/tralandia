<?php
/**
 * LICENSE: The MIT License
 * Copyright (c) 2010 Chris Nizzardini (http://www.cnizz.com)
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * largely a wrapper class for php imap functions but since the classes on phpclasses.org are so shitty here we go....
 * @see http://www.php.net/manual/en/book.imap.php
 * @uses imap_mailboxmsginfo
 * @uses imap_headers
 * @uses imap_list
 * @uses imap_headerinfo
 */

namespace Horo;


class Imap {

	private $stream;
	private $mbox;
	private $isConnected = 0;
	private $host;
	private $username;
	private $password;
	private $folder;
	private $port;
	private $tls;

	function __construct($host, $username, $password, $folder='INBOX', $port=143, $tls='notls') {
		$this->mbox = '{'.$host.':'.$port.'/'.$tls.'}'.$folder;
		$this->stream = imap_open($this->mbox,$username,$password);
		if($this->stream != false) {
			$this->isConnected = 1;
		}
	}

	/**
	 * getMailBoxInfo - returns general mailbox information
	 * @see http://www.php.net/manual/en/function.imap-mailboxmsginfo.php
	 * @param void
	 * @return object
	 */
	public function getMailBoxInfo() {
		return imap_mailboxmsginfo($this->stream);
	}

	/**
	 * getHeaders - this is all you need to see email header information for all emails within a mailbox
	 * @param void
	 * @return array
	 */
	public function getHeaders() {
		$headers = FALSE;

		$arr = $this->getImapHeaders();

		if(is_array($arr)) {

			foreach($arr as $i) {
				$i = trim($i);
				// check for unread emails

				if(substr($i,0,1) == 'U'){
					$i = substr($i,1,strlen($i));
				}

				$i = trim($i);
				// display if not pending deletion

				if(substr($i,0,1) != 'D'){
					$position = strpos($i,')');
					$msgno = substr($i,0,$position);
					$headers[] = $this->getHeader($msgno);
				}
			}
		}

		return $headers;
	}

	public function getMessageIds($filterData = FALSE) {
		$uids = FALSE;
		$type = FALSE;
		$filter = FALSE;

		if(is_array($filterData)) {
			if($filterData['type']) {
				$type = $filterData['type'];
			}

			if($filterData['filter']) {
				$filter = $filterData['filter'];
			}
		} else {
			$filter = $filterData;
		}

		switch ($filter) {
			case 'all':
				$uids = imap_search( $this->stream, 'ALL');
				break;

			case 'body':
				#search term in body
				$uids = imap_search( $this->stream, 'BODY "'.$type.'"');
				break;

			case 'subject':
				#search term in body
				$uids = imap_search( $this->stream, 'SUBJECT "'.$type.'"');
				break;

			case 'text':
				#search term in body
				$uids = imap_search( $this->stream, 'TEXT "'.$type.'"');
				break;

			case 'from':
				#search term in body
				$uids = imap_search( $this->stream, 'FROM "'.$type.'"');
				break;

			case 'to':
				#search term in body
				$uids = imap_search( $this->stream, 'TO "'.$type.'"');
				break;

			case 'answered':
				$uids = imap_search( $this->stream, 'ANSWERED');
				break;

			case 'deleted':
				$uids = imap_search( $this->stream, 'DELETED');
				break;

			case 'flagged':
				$uids = imap_search( $this->stream, 'FLAGGED');
				break;

			case 'new':
				$uids = imap_search( $this->stream, 'NEW');
				break;

			case 'old':
				$uids = imap_search( $this->stream, 'OLD');
				break;

			case 'recent':
				$uids = imap_search( $this->stream, 'RECENT');
				break;

			case 'seen':
				$uids = imap_search( $this->stream, 'SEEN');
				break;

			case 'unanswered':
				$uids = imap_search( $this->stream, 'UNANSWERED');
				break;

			case 'undeleted':
				$uids = imap_search( $this->stream, 'UNDELETED');
				break;

			case 'unflagged':
				$uids = imap_search( $this->stream, 'UNFLAGGED');
				break;

			case 'unseen':
				$uids = imap_search( $this->stream, 'UNSEEN');
				break;

			default:
				$uids = imap_search( $this->stream, 'UNSEEN');
		}

		return $uids;
	}

	/**
	 * getMailboxList - returns lists of mailboxes
	 * @see http://www.php.net/manual/en/function.imap-list.php
	 * @param void
	 * @return array
	 */
	public function getMailboxList() {
		return imap_list($this->stream,$this->mbox,'*');
	}

	/**
	 * getHeader
	 * This returns detailed header information for the given message number
	 * @param messageNumber
	 * @return array
	 */
	public function getHeader($messageNumber = FALSE) {
		$header = FALSE;

		if($messageNumber) {

			$head = $this->getHeaderInfo($messageNumber);

			$header['date'] = $head->date;
			$header['time'] = strtotime($head->date);
			$header['subject'] = Sanity::mimeDecode($head->subject);
			$header['toaddress'] = $head->toaddress;
			$header['message_id'] = $head->message_id;
			#$header['from'] = $head->from[0]->mailbox.'@'.$head->from[0]->host;
			#$header['sender'] = $head->sender[0]->mailbox.'@'.$head->sender[0]->host;
			$header['reply_toaddress'] = Sanity::mimeDecode($head->reply_toaddress);
			$header['size'] = $head->Size;
			$header['msgno'] = trim($head->Msgno);
			$header['udate'] = $head->udate;

			if($head->Unseen == 'U') {
				$header['status'] = 'Unread';
			} else {
				$header['status'] = 'Read';
			}

	  		if(is_array($head->to)) {
				$to = $head->to[0];

	  			$header['to']['personal'] = $to->personal;
	  			$header['to']['mailbox'] = $to->mailbox;
	  			$header['to']['host'] = $to->host;

	  			if($to->mailbox && $to->host) {
	  				$header['to']['email'] = $to->mailbox.'@'.$to->host;
	  			}
	  		}

	  		if($head->from[0]) {
				$from = $head->from[0];

	  			$header['from']['personal'] = $from->personal;
	  			$header['from']['mailbox'] = $from->mailbox;
	  			$header['from']['host'] = $from->host;

	  			if($from->mailbox && $from->host) {
	  				$header['from']['email'] = $from->mailbox.'@'.$from->host;
	  			}
	  		}

	  		if($head->reply_to[0]) {
				$replyTo = $head->reply_to[0];

	  			$header['reply_to']['personal'] = $replyTo->personal;
	  			$header['reply_to']['mailbox'] = $replyTo->mailbox;
	  			$header['reply_to']['host'] = $replyTo->host;

	  			if($replyTo->mailbox && $replyTo->host) {
	  				$header['reply_to']['email'] = $replyTo->mailbox.'@'.$replyTo->host;
	  			}
	  		}

	  		if($head->sender[0]) {
				$sender = $head->sender[0];

	  			$header['sender']['personal'] = $sender->personal;
	  			$header['sender']['mailbox'] = $sender->mailbox;
	  			$header['sender']['host'] = $sender->host;

	  			if($sender->mailbox && $sender->host) {
	  				$header['sender']['email'] = $sender->mailbox.'@'.$sender->host;
	  			}
	  		}
		}

		return $header;
	}

	/**
	 * getMessage
	 * This returns the entire email for the given message number
	 * 
	 * @param unknown_type $messageNumber
	 * @return array
	 * @example array('header'=>object,'plain'=>'','html'=>'','attachment'=>array());
	 */
	public function getMessage($messageNumber = FALSE, $getAttachments = FALSE) {
		$message = FALSE;
		$charset = 'UTF-8';

		$emailMessage = new EmailMessage($this->stream, $messageNumber);
		$emailMessage->getAttachments = $getAttachments;
		$emailMessage->fetch();
		
		$message = $this->getHeader($messageNumber);
		$message['plain'] = $emailMessage->bodyPlain;
		$message['bodyHtml'] = $emailMessage->bodyHTML;
		$message['attachments'] = $emailMessage->attachments;

		return $message;
	}

	public function getAttachment($data = FALSE) {
		$attachment = FALSE;

		if (is_array($data)) {
			$attachment = Sanity::doEncoding($data['encoding'], $this->getBodyStr($data['msgno'], $data['part']));
		}

		return $attachment;
	}

	public function markDelete($messageNumber = FALSE) {
		return imap_delete($this->stream, $messageNumber);
	}

	public function expunge() {
		return imap_expunge($this->stream);
	}


	public function isConnected() {
		return $this->isConnected;
	}

	public function mbox() {
		return $this->mbox;
	}

	public function host() {
		return $this->host;
	}

	public function username() {
		return $this->username;
	}

	public function password() {
		return $this->password;
	}

	public function folder() {
		return $this->folder;
	}

	public function port() {
		return $this->port;
	}

	public function tls() {
		return $this->tls;
	}

	public function stream() {
		return $this->stream;
	}

	public function move($id = false, $label = false) {
		if($id && $label) {
			return imap_mail_move($this->stream, $id, $label); 
		} else {
			return false;
		}
	}

	// METHODS BELOW ARE PRIVATE - YOU CAN CHANGE THESE TO PUBLIC IF NEED BE - BUT THE ABOVE METHODS SHOULD GIVE YOU EVERYTHING YOU NEED

	/**
	 * getHeaderInfo
	 * @see http://www.php.net/manual/en/function.imap-headerinfo.php
	 * @param void
	 * @return object
	 */
	private function getHeaderInfo($messageNumber){
		return @imap_headerinfo($this->stream,$messageNumber);
	}

	/**
	 * getMessageStructure
	 * @see http://www.php.net/manual/en/function.imap-fetchstructure.php
	 * @param unknown_type $messageNumber
	 * @return object
	 */
	private function getMessageStructure($messageNumber){
		return imap_fetchstructure($this->stream, $messageNumber);
	}

	/**
	 * getRawBody
	 * @see http://www.php.net/manual/en/function.imap-body.php
	 * @param unknown_type $messageNumber
	 * @return string
	 */
	private function getRawBody($messageNumber){
		return imap_body($this->stream, $messageNumber);
	}

	/**
	 * getImapHeaders - returns general info on emails in this box
	 * @see http://www.php.net/manual/en/function.imap-headers.php
	 * @param void
	 * @return array
	 */
	private function getImapHeaders(){
		return imap_headers($this->stream);
	}

	/**
	 * getBodyStructure
	 * @see http://www.php.net/manual/en/function.imap-bodystruct.php
	 * @param $messageNumber(int),part(int)
	 * @return object
	 */
	private function getBodyStructure($messageNumber, $part){
		return imap_bodystruct($this->stream, $messageNumber, $part);
	}

	/**
	 * getBodyStr
	 * @see http://www.php.net/manual/en/function.imap-fetchbody.php
	 * @param $messageNumber(int),part(int)
	 * @return string
	 */
	private function getBodyStr($messageNumber,$section){
		return imap_fetchbody($this->stream,$messageNumber,$section);
	}


}
