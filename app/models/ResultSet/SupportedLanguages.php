<?php

use Doctrine\ORM\EntityManager;

class SupportedLanguages {

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var ResultSorter
	 */
	private $resultSorter;


	public function __construct(EntityManager $em, ResultSorter $resultSorter)
	{
		$this->em = $em;
		$this->resultSorter = $resultSorter;
	}


	public function getSortedResult()
	{
		$result = $this->em->getRepository(LANGUAGE_ENTITY)->findSupported();
		$result = $this->resultSorter->translateAndSort($result);
		return $result;
	}


	public function getSortedByIso()
	{
		$result = $this->em->getRepository(LANGUAGE_ENTITY)->findSupported(['iso' => 'ASC']);
		return $result;
	}

}
