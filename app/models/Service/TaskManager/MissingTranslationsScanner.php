<?php

namespace Service\TaskManager;


/**
 * MissingTranslationsScanner class
 *
 * @author Dávid Ďurika
 */
class MissingTranslationsScanner extends \Nette\Object implements IScanner {

	protected $languageRepository, $phraseRepository;

	public function __construct() {
		list($this->languageRepository, $this->phraseRepository) = func_get_args();
	}

	public function needToRun() {
		return true;
	}

	public function run() {
		
		return true;
	}

}