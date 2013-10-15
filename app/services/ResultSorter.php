<?php

class ResultSorter {


	/**
	 * @var \Tralandia\Localization\Translator
	 */
	private $translator;

	/**
	 * @var Environment\Collator
	 */
	private $collator;


	/**
	 * @param \Tralandia\Localization\Translator $translator
	 * @param \Environment\Collator $collator
	 */
	public function __construct(\Tralandia\Localization\Translator $translator, \Environment\Collator $collator) {

		$this->translator = $translator;
		$this->collator = $collator;
	}


	/**
	 * @param $result
	 * @param $translatedValueCallback
	 *
	 * @return array
	 */
	public function translateAndSort($result, $translatedValueCallback = NULL)
	{
		if($translatedValueCallback === NULL) {
			$translatedValueCallback = function($v) {return $v->getName();};
		}
		$translatedResult = $this->translate($result, $translatedValueCallback);
		return $this->sort($result, $translatedResult);
	}


	/**
	 * @param $result
	 * @param $translatedValueCallback
	 *
	 * @return array
	 */
	protected function translate($result, $translatedValueCallback)
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
	protected function sort($result, $translatedResult)
	{
		$return = [];
		$this->collator->asort($translatedResult);
		foreach($translatedResult as $key => $value) {
			$return[$key] = $result[$key];
		}

		return $return;
	}

}
