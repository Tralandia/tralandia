<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 6/17/13 8:24 PM
 */

namespace Mail;


use Nette;

class SendmailMailer extends Nette\Mail\SendmailMailer
{
	use TMailer;

	public function send(Nette\Mail\Message $message)
	{
		$message = $this->prepareMessage($message);
		parent::send($message);
	}

}
