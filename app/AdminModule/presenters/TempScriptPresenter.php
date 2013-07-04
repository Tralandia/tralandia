<?php

namespace AdminModule;


use Entity\Phrase\Translation;

class TempScriptPresenter extends BasePresenter {


	public function actionCreateMissingTranslationsForLocations()
	{

		$qb = $this->em->getRepository(LOCATION_ENTITY)->createQueryBuilder('e');
		$qb->innerJoin('e.name', 'n')
			->leftJoin('n.translations', 't')
			->groupBy('e.id')
			->having('count(t.id) = 0');

		$locations = $qb->getQuery()->getResult();

		/** @var $location \Entity\Location\Location */
		foreach($locations as $location) {
			$parent = $location->getPrimaryParent();
			$language = $parent->getDefaultLanguage();
			$name = $location->getName();

			if(!$parent || !$language || !$name) {
				throw new \Exception('haaat je tu hyba!');
			}
			$name->createTranslation($language, $location->getLocalName());
		}
		$this->em->flush();

		$this->sendPayload();
	}


	public function actionUpdateReservationEmail()
	{
		/** @var $emailTemplate \Entity\Email\Template */
		$emailTemplate = $this->em->getRepository(EMAIL_TEMPLATE_ENTITY)->findOneBySlug('reservation-form');

		$body = $emailTemplate->getBody();

		$pattern = '#\[(reservation_senderName|reservation_senderEmail|reservation_senderPhone|reservation_arrivalDate|reservation_departureDate|reservation_adultsCount|reservation_childrenCount|reservation_message)\]#';
		foreach($body->getTranslations() as $translation) {
			if($translation->getLanguage()->getIso() == 'en') continue;
			if($translation->getLanguage()->getIso() == 'sk') continue;
			$t = $translation->getTranslation();
			$tNew = preg_replace($pattern, "[$1]\n", $t);

			$translation->setTranslation($tNew);
		}

		$this->em->flush();
	}


	public function actionCountTranslatedWords($id)
	{
		$iso = $id;
		/** @var $language \Entity\Language */
		$language = $this->em->getRepository(LANGUAGE_ENTITY)->findOneByIso($iso);

		if(!$language) throw new \Exception('Zly iso kod jazyka!');

		/** @var $translationsRepository \Repository\Phrase\TranslationRepository */
		$translationsRepository = $this->em->getRepository(TRANSLATION_ENTITY);

		$qb = $translationsRepository->createQueryBuilder();
		$qb = $translationsRepository->filterTranslatedTypes($qb);

		$qb->andWhere($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language)
			->andWhere($qb->expr()->eq('e.status', ':status'))->setParameter('status', Translation::UP_TO_DATE);

		$translations = $qb->getQuery()->getResult();

		$this->payload->translationsCount = count($translations);
		$this->payload->wordsCount = $translationsRepository->calculateWordsInTranslations($translations);
		$this->payload->langauge = $language->getName()->getCentralTranslationText();
		$this->payload->langaugeId = $language->getId();
		$this->payload->langaugeIso = $language->getIso();
		$this->sendPayload();
	}


	public function actionRenameZero()
	{
		$languages = $this->em->getRepository(LANGUAGE_ENTITY)->findAll();

		/** @var $language \Entity\Language */
		foreach($languages as $language) {
			$plurals = $language->getPlurals();
			$names = $plurals['names'];

			$names[1] = str_replace('Zero', '0', $names[1]);
			if(array_key_exists(2, $names)) $names[2] = str_replace('Zero', '0', $names[2]);

			$plurals['names'] = $names;

			$language->setPlurals($plurals);
		}

		$this->em->flush();
	}

}
