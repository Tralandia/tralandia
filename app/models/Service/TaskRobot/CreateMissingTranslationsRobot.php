<?php

// namespace Service\TaskRobot;


// /**
//  * MissingTranslationsRobot class
//  *
//  * @author Dávid Ďurika
//  */
// class CreateMissingTranslationsRobot extends \Nette\Object implements ITaskRobot {

// 	protected $languageRepository, $phraseRepository;

// 	public function __construct() {
// 		list($this->languageRepository, $this->phraseRepository) = func_get_args();
// 	}

// 	public function needToRun() {
// 		return true;
// 	}

// 	public function run() {
// 		$languages = $this->languageRepository->findSupported();
// 		$missing = $this->phraseRepository->findMissingTranslations($languages);
// 		return $missing;
// 	}

// }