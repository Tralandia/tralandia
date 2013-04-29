<?php

namespace Robot;

use DictionaryManager\UpdateTranslationStatus;
use Doctrine\ORM\EntityManager;

/**
 * UpdateTranslationStatusRobot class
 *
 * @author Dávid Ďurika
 */
class UpdateTranslationStatusRobot extends \Nette\Object implements IRobot
{

	/**
	 * @var \DictionaryManager\UpdateTranslationStatus
	 */
	protected $updateTranslationStatus;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	public function __construct(UpdateTranslationStatus $updateTranslationStatus, EntityManager $em)
	{
		$this->updateTranslationStatus = $updateTranslationStatus;
		$this->em = $em;
	}


	public function needToRun()
	{
		return TRUE;
	}


	public function run()
	{
		$types = $this->em->getRepository(PHRASE_TYPE_ENTITY)->findByTranslated(TRUE);
		$phrases = $this->em->getRepository(PHRASE_ENTITY)->findByType($types);

		foreach($phrases as $phrase) {
			$this->updateTranslationStatus->updatePhrase($phrase);
		}

		$this->em->flush();
	}
}
