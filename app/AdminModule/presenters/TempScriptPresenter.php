<?php

namespace AdminModule;


use Doctrine\ORM\QueryBuilder;
use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;

class TempScriptPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Dictionary\UpdateTranslationVariations
	 */
	protected $variationUpdater;


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


	public function actionUpdateTranslationsForLanguage($iso, $limit, $offset)
	{
		/** @var $language \Entity\Language */
		$language = $this->em->getRepository(LANGUAGE_ENTITY)->findOneByIso($iso);


		/** @var $translationsRepository \Repository\Phrase\TranslationRepository */
		$translationsRepository = $this->em->getRepository(TRANSLATION_ENTITY);

		$qb = $translationsRepository->createQueryBuilder();
		$qb = $translationsRepository->filterTranslatedTypes($qb);

		$qb->andWhere($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language);

		$translations = $qb->getQuery()->getResult();

		foreach($translations as $translation) {
			$this->variationUpdater->update($translation);
		}

		$this->em->flush();


		$this->payload->success = true;
		$this->sendPayload();
	}


	public function actionCreatePhraseForRoomTypesPriceText()
	{
		$roomTypes = $this->em->getRepository(ROOM_TYPE_ENTITY)->findAll();
		$en = $this->em->getRepository(LANGUAGE_ENTITY)->findOneByIso('en');
		$sk = $this->em->getRepository(LANGUAGE_ENTITY)->findOneByIso('sk');


		$translations = [
			'room' => [
				'en' => 'Price per one room',
				'sk' => 'Cena za jednu izbu',
			],
			'apartment' => [
				'en' => 'Price per one apartment',
				'sk' => 'Cena za jeden apartmán',
			],
			'building' => [
				'en' => 'Price per building',
				'sk' => 'Cena za celý objekt',
			]
		];


		$phraseCreator = new \Service\Phrase\PhraseCreator($this->em);
		/** @var $roomType \Entity\Rental\RoomType */
		foreach($roomTypes as $roomType) {
			$textPriceFor = $roomType->getTextPriceFor();
			if($textPriceFor instanceof Phrase) continue;

			$phraseTypeName = '\Entity\Rental\RoomType:textPriceFor';
			$roomType->setTextPriceFor($phraseCreator->create($phraseTypeName));

			$textPriceFor = $roomType->getTextPriceFor();

			$translation = $translations[$roomType->getSlug()]['en'];
			$textPriceFor->getTranslation($en)->setTranslation($translation);
			$translation = $translations[$roomType->getSlug()]['sk'];
			$textPriceFor->getTranslation($sk)->setTranslation($translation);
		}

		$this->em->flush();

		$this->payload->success = true;
		$this->sendPayload();
	}


	public function actionSendOldReservations()
	{
		/** @var $reservationOwnerEmailListener \Listener\ReservationOwnerEmailListener */
		$reservationOwnerEmailListener = $this->getContext()->getByType('Listener\ReservationOwnerEmailListener');
		/** @var $reservationSenderEmailListener \Listener\ReservationSenderEmailListener */
		$reservationSenderEmailListener = $this->getContext()->getByType('Listener\ReservationSenderEmailListener');

		/** @var $qb QueryBuilder */
		$qb = $this->em->getRepository(RESERVATION_ENTITY)->createQueryBuilder('r');

		$qb->andWhere($qb->expr()->gte('r.created', ':from'))->setParameter('from', '2013-07-15');
		$qb->andWhere($qb->expr()->lt('r.created', ':to'))->setParameter('to', '2013-07-22');
		$qb->andWhere($qb->expr()->orX(
				$qb->expr()->gt('r.arrivalDate', ':arrival'),
				$qb->expr()->isNull('r.arrivalDate')
			))->setParameter('arrival', '2013-07-26');

		$reservations = $qb->getQuery()->getResult();
		foreach($reservations as $reservation) {
			$reservationOwnerEmailListener->onReservationSent($reservation);
			$reservationSenderEmailListener->onReservationSent($reservation);
		}
	}

	public function actionUpdatePricelistFileSize()
	{
		$repository = $this->em->getRepository(RENTAL_PRICELIST_ENTITY);
		$all = $repository->findAll();
		/** @var $priceLisManager \RentalPriceListManager */
		$priceLisManager = $this->context->rentalPriceListManager;

		/** @var $row \Entity\Rental\Pricelist */
		foreach($all as $row) {
			$absolutePath = $priceLisManager->getAbsolutePath($row);
			$row->setFileSize(filesize($absolutePath));

			$this->em->flush($row);
		}
	}

}
