<?php

namespace Extras\Mail\Message;

class Message extends \Nette\Mail\Message {

	public function addTo($email, $name = NULL) {
		$envOptions = \Nette\Environment::getConfig('envOptions');
		if($envOptions->sendEmail){
			if($envOptions->email) {
				$name = $email;
				$emial = $envOptions->email;
			} else {
				throw new \Exception('Nedefinoval si `envOptions->emial`!');
			}
		}
		
		parent::addTo($email, $name);
	}

}
