<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 6/18/13 8:47 AM
 */

namespace Service\Phrase;


use Doctrine\ORM\EntityManager;
use Entity\Phrase\Phrase;
use Nette;

class PhraseRemover {

	/**
	 * @var \Repository\Phrase\PhraseRepository
	 */
	private $phraseRepository;


	/**
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$phraseRepository = $em->getRepository(PHRASE_ENTITY);
		$this->phraseRepository = $phraseRepository;
	}


	/**
	 * @param Phrase $phrase
	 */
	public function remove(Phrase $phrase)
	{
		$this->phraseRepository->delete($phrase);
	}

}
