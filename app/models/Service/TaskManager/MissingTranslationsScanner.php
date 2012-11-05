<?php

namespace Service\TaskManager;


/**
 * MissingTranslationsScanner class
 *
 * @author Dávid Ďurika
 */
class MissingTranslationsScanner extends \Nette\Object implements IScanner {

	protected $autopilot, $phraseTranslationRepositoryAccessor, $languageRepositoryAccessor;

	public function __construct($autopilot, $phraseTranslationRepositoryAccessor, $languageRepositoryAccessor) {
		list($this->autopilot, $this->phraseTranslationRepositoryAccessor, $this->languageRepositoryAccessor) = func_get_args();
	}

	public function needToRun() {
		return true;
	}

	public function run() {
		$toTranslate = $this->phraseTranslationRepositoryAccessor->get()->toTranslate();
		foreach ($toTranslate as $translationData) {
			$this->addTask($translationData);
			break;
		}
	}

	public function addTask($translationData) {
		
		$taskService = $this->autopilot->createTask('\Phrase\Translation - Translation Required');
		$taskEntity = $taskService->getEntity();
		$taskEntity->identifier = $translationData['id'];
		$taskEntity->userLanguage = $this->languageRepositoryAccessor->get()->find($translationData['language_id']);
		// $taskEntity->userRole = $this->
		$taskService->save();
	}

}