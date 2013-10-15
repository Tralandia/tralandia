<?php

namespace Tralandia\Language;

use Doctrine\ORM\EntityManager;
use ResultSorter;
use Tools;

class SupportedLanguages
{

	/**
	 * @var ResultSorter
	 */
	private $resultSorter;

	/**
	 * @var Languages
	 */
	private $languages;


	/**
	 * @param Languages $languages
	 * @param ResultSorter $resultSorter
	 */
	public function __construct(Languages $languages, ResultSorter $resultSorter)
	{
		$this->resultSorter = $resultSorter;
		$this->languages = $languages;
	}


	/**
	 * @return array
	 */
	public function getSortedResult()
	{
		$result = $this->languages->findSupported();
		$result = $this->resultSorter->translateAndSort($result);

		return $result;
	}


	public function getSortedByIso()
	{
		$result = $this->languages->findSupported(['iso' => 'ASC']);

		return $result;
	}


	/**
	 * @param $key
	 * @param $value
	 *
	 * @return array
	 */
	public function getForSelect($key = NULL, $value = NULL)
	{
		$rows = $this->getSortedResult();

		if ($key === NULL) $key = function ($key, $value) {
			return $value->getId();
		};
		if ($value === NULL) $value = function ($value) {
			return $value->getIso();
		};

		return Tools::arrayMap($rows, $key, $value);
	}
}
