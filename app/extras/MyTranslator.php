<?php

namespace Extras;

use Service\Dictionary as D;

class MyTranslator implements \Nette\Localization\ITranslator {

	protected $language = 144;

	public function __construct() {
		$this->language = D\Language::get($this->language);
	}
	
	public function translate($message, $node = NULL, $count = NULL, array $variables = NULL) {

		if(!$message instanceof D\Phrase) {
			$phrase = D\Phrase::get($message);
		}
		
		$message = $phrase->getTranslation($this->language)->translation;
		
		if (is_array($message))
			$message = current($message);

		$args = func_get_args();
		// @todo brano tento celi IF by som zrusli, naco tu je ?
		if (count($args) > 1) {
			array_shift($args);
			if (is_array(current($args)) || current($args) === NULL)
				array_shift($args);

			if (count($args) == 1 && is_array(current($args)))
				$args = current($args);

			$message = str_replace(array("%label", "%name", "%value"), array("#label", "#name", "#value"), $message);
			if (count($args) > 0 && $args != NULL);
			$message = vsprintf($message, $args);
			$message = str_replace(array("#label", "#name", "#value"), array("%label", "%name", "%value"), $message);
		}
		return $message;
	}
	
	public static function getTranslator() {
		return new static();
	}
}