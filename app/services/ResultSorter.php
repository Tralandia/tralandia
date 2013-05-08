<?php

use Doctrine\ORM\EntityManager;

class ResultSorter {


	/**
	 * @var Extras\Translator
	 */
	private $translator;

	/**
	 * @var Environment\Collator
	 */
	private $collator;

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	private $em;


	/**
	 * @param EntityManager $em
	 * @param \Extras\Translator $translator
	 * @param \Environment\Collator $collator
	 */
	public function __construct(EntityManager $em, \Extras\Translator $translator, \Environment\Collator $collator) {

		$this->translator = $translator;
		$this->collator = $collator;
		$this->em = $em;
	}


	/**
	 * @param $result
	 * @param $translatedValueCallback
	 *
	 * @return array
	 */
	public function translateAndSortResult($result, $translatedValueCallback)
	{
		$translatedResult = $this->translateResult($result, $translatedValueCallback);
		return $this->sortResult($result, $translatedResult);
	}


	/**
	 * @param $result
	 * @param $translatedValueCallback
	 *
	 * @return array
	 */
	protected function translateResult($result, $translatedValueCallback)
	{
		$return = [];
		$translatedValueCallback = new Nette\Callback($translatedValueCallback);
		foreach($result as $key => $row) {
			$toTranslate = $translatedValueCallback->invokeArgs([$row]);
			$return[$key] = $this->translator->translate($toTranslate);
		}

		return $return;
	}


	/**
	 * @param $result
	 * @param $translatedResult
	 *
	 * @return array
	 */
	protected function sortResult($result, $translatedResult)
	{
		$return = [];
		$this->collator->asort($translatedResult);
		foreach($translatedResult as $key => $value) {
			$return[$key] = $result[$key];
		}

		return $return;
	}

}
