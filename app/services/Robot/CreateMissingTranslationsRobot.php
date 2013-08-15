<?php

namespace Robot;

use Doctrine\ORM\EntityManager;
use Entity\Language;
use Entity\Phrase\Translation;


/**
 * MissingTranslationsRobot class
 *
 * @author Dávid Ďurika
 */
class CreateMissingTranslationsRobot extends \Nette\Object implements IRobot {

	protected $phraseDecoratorFactory;

	/**
	 * @var \Repository\Phrase\PhraseRepository
	 */
	protected $phraseRepository;

	/**
	 * @var \Repository\LanguageRepository
	 */
	protected $languageRepository;

	public function __construct(EntityManager $em)
	{
		$this->phraseRepository = $em->getRepository(PHRASE_ENTITY);
		$this->languageRepository = $em->getRepository(LANGUAGE_ENTITY);
	}

	public function needToRun() {
		return TRUE;
	}

	public function run() {
		$missing = $this->phraseRepository->findMissingTranslations();
		return $this->_run($missing);
	}


	public function runFor(Language $language)
	{
		$missing = [];
		$missing[$language->getId()] = $this->phraseRepository->findMissingTranslationsBy($language, 1500);
		return $this->_run($missing);
	}


	public function needToRunFor(Language $language)
	{
		return $this->phraseRepository->findMissingTranslationsCountBy($language);
	}


	protected function _run($missing)
	{
		$languages = $this->languageRepository->findSupported();
		foreach ($languages as $language) {
			if(isset($missing[$language->id])) {
				/** @var $phrase \Entity\Phrase\Phrase */
				foreach ($missing[$language->id] as $phrase) {
					$phrase->createTranslation($language);
				}
			}
		}
		$this->phraseRepository->flush();

		return $missing;
	}

}
