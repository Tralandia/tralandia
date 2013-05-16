<?php

namespace Robot;

use Dictionary\UpdateTranslationStatus;
use Doctrine\ORM\EntityManager;
use Nette\InvalidArgumentException;
use Nette\Utils\Paginator;

/**
 * UpdateTranslationStatusRobot class
 *
 * @author Dávid Ďurika
 */
class UpdateTranslationStatusRobot extends \Nette\Object implements IRobot
{

	protected $itemsPerIteration = 100;

	protected $itemCount = 0;

	/**
	 * @var Paginator
	 */
	protected $paginator;

	/**
	 * @var \Dictionary\UpdateTranslationStatus
	 */
	protected $updateTranslationStatus;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var \Repository\Phrase\PhraseRepository
	 */
	protected $phraseRepository;

	public function __construct(UpdateTranslationStatus $updateTranslationStatus, EntityManager $em)
	{
		$this->updateTranslationStatus = $updateTranslationStatus;
		$this->em = $em;
		$this->phraseRepository = $this->em->getRepository(PHRASE_ENTITY);
	}


	public function setCurrentIteration($iteration)
	{
		$paginator = $this->getPaginator();
		$paginator->setPage($iteration);
	}


	public function getIterationCont()
	{
		return $this->getPaginator()->getPageCount();
	}


	public function getNextIteration()
	{
		return $this->getPaginator()->getPage() + 1;
	}


	public function needToRun()
	{
		return $this->getNextIteration() <= $this->getIterationCont();
	}


	public function run()
	{
		$paginator = $this->getPaginator();
		$qb = $this->phraseRepository->findTranslatedQb();
		$qb->setFirstResult($paginator->getOffset())
			->setMaxResults($paginator->getItemsPerPage());

		$phrases = $qb->getQuery()->getResult();

		foreach($phrases as $phrase) {
			$this->updateTranslationStatus->updatePhrase($phrase);
			$this->em->flush();
		}
	}


	/**
	 * @return Paginator
	 */
	protected function getPaginator()
	{
		if(!$this->paginator) {
			$paginator = new Paginator();
			$paginator->setItemsPerPage($this->itemsPerIteration);
			$paginator->setItemCount($this->getItemCount());
			$this->paginator = $paginator;
		}

		return $this->paginator;
	}


	protected function getItemCount()
	{
		if(!$this->itemCount) {
			$this->itemCount = $this->phraseRepository->getTranslatedCount();
		}

		return $this->itemCount;
	}
}
