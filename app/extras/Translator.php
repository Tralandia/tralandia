<?php

namespace Extras;

use Service\Dictionary as D;

class Translator implements \Nette\Localization\ITranslator {

	protected $language = 144;

	public function __construct(Environment $environment, \Nette\Caching\IStorage $sorage) {
		$this->language = $environment->getLanguage();
	}
	
	public function translate($message, $node = NULL, $count = NULL, array $variables = NULL) {

		if(!$message instanceof D\Phrase) {
			$phrase = D\Phrase::get($message);
		}
		
		$message = $phrase->getTranslation($this->language)->translation;
		
		if (is_array($message))
			$message = current($message);

		return $message;
	}
	
	public static function getTranslator() {
		return new static();
	}
}