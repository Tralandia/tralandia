<?php

class MyTranslator implements \Nette\Localization\ITranslator {
	
	public function translate($message, $count = NULL) {
		return $message;
	}
	
	public static function getTranslator() {
		return new static();
	}
}