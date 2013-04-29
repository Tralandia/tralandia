<?php

namespace Robot;


/**
 * MissingTranslationsRobot class
 *
 * @author Dávid Ďurika
 */
class CreateMissingTranslationsRobot extends \Nette\Object implements IRobot {

	protected $phraseDecoratorFactory;

	protected $phraseRepositoryAccessor;
	protected $languageRepositoryAccessor;

	public function inject(\Model\Phrase\IPhraseDecoratorFactory $phraseDecoratorFactory) {
		$this->phraseDecoratorFactory = $phraseDecoratorFactory;
	}

	public function injectDic(\Nette\DI\Container $dic) {
		$this->phraseRepositoryAccessor = $dic->phraseRepositoryAccessor;
		$this->languageRepositoryAccessor = $dic->languageRepositoryAccessor;
	}

	public function needToRun() {
		return true;
	}

	public function run() {
		$missing = $this->phraseRepositoryAccessor->get()->findMissingTranslations();

		$langauges = $this->languageRepositoryAccessor->get()->findById(array_keys($missing));
		foreach ($langauges as $language) {
			foreach ($missing[$language->id] as $phrase) {
				$phraseService = $this->phraseDecoratorFactory->create($phrase);
				$translaion = $phraseService->createTranslation($language);
			}
		}
		$this->phraseRepositoryAccessor->get()->flush();

		return $missing;
	}

}
