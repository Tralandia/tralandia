<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 6/21/13 1:08 PM
 */

namespace Diagnostics;


use Nette;

class ErrorLogger extends Nette\Diagnostics\Logger {


	public $mailer = array(__CLASS__, 'logSender');

	/**
	 * @param $message
	 * @param $email
	 */
	public static function logSender($message, $email)
	{
		$smsText = Nette\Utils\Strings::truncate($message, 150);
		$t[] = 'username=uns';
		$t[] = 'password=ykZ@p8h';
		$t[] = 'sender=TRAxError';
		$t[] = 'message='.urlencode($smsText);

		$rado = $t;
		$david = $t;

		$rado[] = 'recipient=+421902318926';
		$david[] = 'recipient=+421948035665';

		$radoUrl = 'http://www.123sms.sk/api/rest/?'.implode('&', $rado);
		$davidUrl = 'http://www.123sms.sk/api/rest/?'.implode('&', $david);

		$content = file_get_contents($radoUrl);
		$content = file_get_contents($davidUrl);

		$mailer = array('\Nette\Diagnostics\Logger', 'defaultMailer');
		Nette\Callback::create($mailer)->invoke($message, implode(', ', $email));
	}

}
