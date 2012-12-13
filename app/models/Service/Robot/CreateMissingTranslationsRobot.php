<?php

namespace Service\TaskRobot;


/**
 * MissingTranslationsRobot class
 *
 * @author Dávid Ďurika
 */
class CreateMissingTranslationsRobot extends \Nette\Object implements ITaskRobot {

	protected $languageRepositoryAccessor;
	protected $phraseRepositoryAccessor;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->languageRepositoryAccessor = $dic->languageRepositoryAccessor;
		$this->phraseRepositoryAccessor = $dic->phraseRepositoryAccessor;
	}

	public function needToRun() {
		return true;
	}

	public function run() {
		$languages = $this->languageRepositoryAccessor->get()->findSupported();
		$missing = $this->phraseRepositoryAccessor->get()->findMissingTranslations($languages);

		# @todo pridat tasky

		return $missing;
	}

}