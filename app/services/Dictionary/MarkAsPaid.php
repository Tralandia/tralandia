<?php

namespace Dictionary;


use Doctrine\ORM\EntityManager;
use Entity\Language;
use Entity\Phrase\Translation;
use Entity\User\User;

class MarkAsPaid {


	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;


	/**
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	/**
	 * @param Language $language
	 * @param \Entity\User\User $responsibleUser
	 *
	 * @return array
	 */
	public function mark(Language $language, User $responsibleUser)
	{
		/** @var $translationRepository \Repository\Phrase\TranslationRepository */
		$translationRepository = $this->em->getRepository(TRANSLATION_ENTITY);
		$notPaidQb = $translationRepository->findNotPaidQb($language)
			->select('e.id');

		$ids = $notPaidQb->getQuery()->getResult();
		$ids = \Tools::arrayMap($ids, 'id');

		if(count($ids)) {
			$qb = $translationRepository->createQueryBuilder();
			$qb->update(TRANSLATION_ENTITY, 'e')
				->set('e.status', ':status')->setParameter('status', Translation::UP_TO_DATE)
				->set('e.unpaidAmount', ':unpaidAmount')->setParameter('unpaidAmount', NULL)
				->where($qb->expr()->in('e.id', $ids));

			$qb->getQuery()->execute();
		}
		return $ids;
	}

}
