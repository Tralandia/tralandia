<?php

namespace Statistics;


use DataSource\IDataSource;
use Doctrine\ORM\EntityManager;
use Nette\ArrayHash;
use Nette\Utils\Paginator;

class Dictionary implements IDataSource {

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	public function getData($filter, $order, Paginator $paginator = NULL)
	{
		/** @var $translatorRepository \Repository\Phrase\TranslationRepository */
		$translatorRepository = $this->em->getRepository(TRANSLATION_ENTITY);
		$data = $translatorRepository->toTranslate();
		$return = [];
		/** @var $translation \Entity\Phrase\Translation */
		foreach($data as $translation) {
			$languageId = $translation->getLanguage()->getId();
			if(!isset($return[$languageId])) {
				$return[$languageId] = [
					'id' => $languageId,
					'language' => $translation->getLanguage()->getName(),
					'count' => 0,
				];
			}
			$return[$languageId]['count']++;
		}
		return ArrayHash::from($return);
	}
}
