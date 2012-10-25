<?php

namespace Service\TaskManager;


/**
 * MissingTranslationsScanner class
 *
 * @author Dávid Ďurika
 */
class MissingTranslationsScanner extends \Nette\Object implements IScanner {

	protected $autopilot, $phraseTranslationRepository, $languageRepository;

	public function __construct() {
		list($this->autopilot, $this->phraseTranslationRepository, $this->languageRepository) = func_get_args();
	}

	public function needToRun() {
		return true;
	}

	public function run() {
		$toTranslate = $this->phraseTranslationRepository->toTranslate();
		foreach ($toTranslate as $translationData) {
			$this->addTask($translationData);
			break;
		}
	}

	public function addTask($translationData) {
		$taskService = $this->autopilot->createTask('\Phrase\Translation - Translation Required');
		$taskEntity = $taskService->getEntity();
		$taskEntity->identifier = $translationData['id'];
		$taskEntity->userLanguage = $this->languageRepository->find($translationData['language_id']);
		// $taskEntity->userRole = $this->
		$taskService->save();
	}

}