<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 6/7/13 2:16 PM
 */

namespace Mail;

use Nette;
use Nette\Diagnostics\Debugger;
use Nette\Mail\Message;

class EmailerMailer extends \Nette\Mail\SendmailMailer
{

	use TMailer;
	use TBaseMailerConstruct;

	/**
	 * Sends email.
	 *
	 * @param \Nette\Mail\Message $mail
	 *
	 * @return void
	 */
	public function send(Message $mail)
	{
		$mail = $this->prepareMessage($mail);
		$params = [];

		$from = $mail->getFrom();
		$fromKeys = array_keys($from);
		$fromEmail = array_shift($fromKeys);
		$fromName = reset($from);

		$params['from_email'] = $fromEmail;
		$params['from_name'] = $fromName;

		$to = $mail->getHeader('To');
		$toKeys = array_keys($to);
		$toEmail = array_shift($toKeys);
		$toName = reset($to);
		$params['to_email'] = $toEmail;
		$params['to_name'] = $toName;

		$bcc = $mail->getHeader('Bcc');
		if (is_array($bcc)) {
			$bccKeys = array_keys($bcc);
			$bccEmail = array_shift($bccKeys);
		} else {
			$bccEmail = NULL;
		}
		$params['bcc_email'] = $bccEmail;

		$params['subject'] = $mail->getSubject();
		$params['body'] = $mail->getBody();
		$params['body_html'] = $mail->getHtmlBody();

		$this->addEmailToEmailer([$params]);
	}


	private function addEmailToEmailer($params)
	{
		$insert = array();
		foreach ($params as $key => $val) {
			if (Nette\Utils\Validators::isEmail($val['to_email']) && Nette\Utils\Validators::isEmail($val['from_email'])) {
				$t = array();
				$t[] = 5; //(int)$val['urgency'];
				$t[] = 1; //(int)$val['confirmed'];
				$t[] = 1; //(int)$val['batch_id'];
				$t[] = time(); //(int)$val['stamp'];
				$t[] = '"' . $val['from_email'] . '"';
				$t[] = '"' . $val['from_name'] . '"';
				$t[] = '"' . $val['to_email'] . '"';
				$t[] = '"' . $val['to_name'] . '"';
				$t[] = '"' . $val['bcc_email'] . '"';
				$t[] = '"' . $val['subject'] . '"';
				$t[] = '"' . addslashes(gzcompress($val['body'])) . '"';
				$t[] = '"' . addslashes(gzcompress($val['body_html'])) . '"';
				$t[] = '""'; //'"'.$val['attachments'].'"';
				$t[] = '"' . $this->getDomainFromEmail($val['to_email']) . '"';
				$t[] = 0; // test

				$insert[] = '(' . implode(', ', $t) . ')';
			}
		}

		if (count($insert)) {
			$query = 'INSERT INTO emailer_queued (urgency, confirmed, batch_id, stamp, from_email, from_name, to_email, to_name, bcc_email, subject, body, body_html, attachments, domain, test) VALUES ' . implode(', ', $insert);
			//ape($query);
			return (bool)$this->qEmailer($query);
		} else {
			return TRUE;
		}
	}


	private function qEmailer($query)
	{
		static $emailerConnection;
		if (!$emailerConnection) {
			$config = array(
				'host' => 'tra-emailer.soft1.sk',
				'user' => 'emailer',
				'password' => '974jd7YRN32CO',
				'database' => 'emailer',
			);
			$emailerConnection = mysql_connect($config['host'], $config['user'], $config['password']);
			mysql_select_db($config['database'], $emailerConnection);
			mysql_query("SET NAMES utf8");
		}
		//if(Invoicing::$testMode) pr($query);

		//\Nette\Diagnostics\Debugger::log($query, 'emailer_query');

		if ($r = @mysql_query($query, $emailerConnection)) {
			if (stripos($query, "insert into emailer") !== FALSE) {
				$r = mysql_insert_id($emailerConnection);
			}

			return $r;
		} else {
			$error = new \Exception(mysql_error($emailerConnection));
			\Nette\Diagnostics\Debugger::log($error->getMessage(), 'emailer_query');
			throw $error;
			//return FALSE;
		}
	}


	private function getDomainFromEmail($email)
	{
		if (Nette\Utils\Validators::isEmail($email)) {
			$domain = strstr($email, '@');
			$domain = substr($domain, 1, strpos($domain, '.') - 1);
		} else {
			$domain = FALSE;
		}

		return $domain;
	}
}
