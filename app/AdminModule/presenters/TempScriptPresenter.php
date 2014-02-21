<?php

namespace AdminModule;


use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Types\Json;
use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Entity\User\Role;
use Environment\Environment;
use Nette\UnknownImageFileException;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;

class TempScriptPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Robot\GeneratePathSegmentsRobot
	 */
	protected $generatePathSegmentsRobot;

	/**
	 * @autowire
	 * @var \Dictionary\UpdateTranslationVariations
	 */
	protected $variationUpdater;

	/**
	 * @autowire
	 * @var \Robot\CreateMissingTranslationsRobot
	 */
	protected $createMissingTranslationsRobot;

	/**
	 * @autowire
	 * @var \Service\PolygonService
	 */
	protected $polygonCalculator;

	/**
	 * @autowire
	 * @var \User\UserCreator
	 */
	protected $userCreator;

	/**
	 * @autowire
	 * @var \Tralandia\Rental\Rentals
	 */
	protected $rentals;

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


	public function actionCreateMissingTranslations()
	{
		$languages = $this->em->getRepository(LANGUAGE_ENTITY)->findSupported();
		foreach($languages as $language) {
			$this->createMissingTranslationsRobot->runFor($language);
		}
		$this->sendPayload();
	}

	public function actionFixBrokenLocations()
	{
		$addressRepository = $this->em->getRepository(ADDRESS_ENTITY);
		$locations = $this->em->getRepository(LOCATION_ENTITY)->findBy(['slug' => NULL]);
		/** @var $locationDecoratorFactory \Model\Location\ILocationDecoratorFactory */
		$locationDecoratorFactory = $this->context->locationDecoratorFactory;

		/** @var $location \Entity\Location\Location */
		foreach($locations as $location) {
			$name = $location->getName()->getSourceTranslationText();

			$newSlug = \Tools::transliterate($name);
			$newSlug = Strings::webalize($newSlug);

			$existingLocation = $this->locationDao->findOneBy(array(
				'parent' => $location->getParent(),
				'slug' => $newSlug,
			));

			if($existingLocation && $location->getId() != $existingLocation->getId()) {
				$addresses = $addressRepository->findByLocality($location);
				/** @var $address \Entity\Contact\Address */
				foreach($addresses as $address) {
					$address->setLocality($existingLocation);
				}

				$this->em->remove($location);
			} else {
				$locationService = $locationDecoratorFactory->create($location);

				$locationService->updateLocalName();
				$locationService->updateSlug();
			}

			$this->em->flush();
		}

	}


	public function actionGeneratePathSegments($id)
	{
		$generatePathSegmentsRobot = $this->generatePathSegmentsRobot;

		if($id == 'pages') {
			$qb = $this->em->createQueryBuilder();
			$qb->delete(PATH_SEGMENT_ENTITY, 'e')->where('e.type = ?1')->setParameter(1, 2);
			$qb->getQuery()->execute();
			$generatePathSegmentsRobot->runPages();
		} else if ($id == 'locations') {
//			$generatePathSegmentsRobot->runLocations();
		} else if ($id == 'types') {
//			$generatePathSegmentsRobot->runTypes();
		}

		$this->terminate();
	}


	public function actionUpdatePolygons($id)
	{
		/** @var $locationRepository \Repository\Location\LocationRepository */
		$locationRepository = $this->locationRepositoryAccessor->get();
		$qb = $locationRepository->createQueryBuilder('e');
		$qb->andWhere($qb->expr()->eq('e.type', 4));
		$qb->andWhere($qb->expr()->notIn('e.polygons', ['false', 'null']));
		$i = 200;
		$qb->setMaxResults($i)->setFirstResult($id * $i);
		$locations = $qb->getQuery()->getResult();

		foreach($locations as $location) {
			$this->polygonCalculator->setRentalsForLocation($location);
		}

		$this->em->flush();
	}

	public function actionUpdatePolygon($id)
	{
		$location = $this->findLocation($id);

		$this->polygonCalculator->setRentalsForLocation($location);

		$this->em->flush();
	}


	public function actionUpdateTranslationsUnpaidAmount($id)
	{
		/** @var $translationRepository \Repository\Phrase\TranslationRepository */
		$translationRepository = $this->em->getRepository(TRANSLATION_ENTITY);

		$qb = $translationRepository->createQueryBuilder('e');

		$qb->andWhere($qb->expr()->in('e.status', ':status'))
			->setParameter('status', [Translation::WAITING_FOR_PAYMENT, Translation::WAITING_FOR_CHECKING]);

		$qb->andWhere($qb->expr()->isNull('e.unpaidAmount'));

		$qb = $translationRepository->filterTranslatedTypes($qb);

		$qb->setMaxResults($id);

		$translations = $qb->getQuery()->getResult();

		/** @var $translation \Entity\Phrase\Translation */
		foreach($translations as $translation) {
			$emptyVariations = $translation->getPhrase()->getTranslationVariationsMatrix($translation->getLanguage());
			$translation->updateUnpaidAmount($emptyVariations);
		}

		$this->em->flush();

		$this->sendPayload();
	}

	public function actionCreateMissingUser()
	{
		$this->userCreator = $this->getContext()->userCreator;
		$translatorFactory = $this->getContext()->getByType('\Tralandia\Localization\ITranslatorFactory');
		$rentalRepository = $this->em->getRepository(RENTAL_ENTITY);
		$qb = $rentalRepository->createQueryBuilder('e');
		$qb->andWhere($qb->expr()->isNull('e.user'));
		$qb->andWhere(($qb->expr()->isNotNull('e.email')));
		$rentals = $qb->getQuery()->getResult();

		/** @var $rental \Entity\Rental\Rental */
		foreach($rentals as $rental){
			$primaryLocation = $rental->getPrimaryLocation();
			$language = $rental->getEditLanguage();

			$environment = new Environment($primaryLocation, $language, $translatorFactory);
			/** @var $user \Entity\User\User */
			$user = $this->userCreator->create($rental->email, $environment, Role::OWNER);
			$user->setPassword(\Nette\Utils\Strings::random(10));

			$user->addRental($rental);

			$this->em->persist($user);
			$this->em->persist($rental);
		}
		$this->em->flush();

		$this->payload->success = true;
		$this->sendPayload();
	}


	public function actionDiscardRentals()
	{
		$emails = ['contact@lecrillon-luberon.com', 'leglikeramia@mail.datanet.hu', 'leglikeramia@mail.datanet.hu', 'leglikeramia@mail.datanet.hu', 'info@zenitpanzio.hu', 'ciaoamico@t-online.hu', 'hotelpannonia@chello.hu', 'info@hotel-molnar.hu', 'aarepriks@hot.ee', 'hortobagy@hnhotels.hu', 'kontakt@holmegaardbedandbreakfast.dk', 'viktoriavendeglo@gmail.com', 'arena.reservation@danubiushotels.com', 'ispan@t-online.hu', 'pensiontokajer@hotmail.com', 'barbara@zelkanet.hu', 'info@posseidon.hu', 'info@noiariik.ee', 'pt.gastro@freemail.hu', 'villagabriella@freemail.hu', 'wiedemanni@spordibaasid.ee', 'klubhotelzrt@gmail.com', 'vaikla@vaikla.ee', 'mediinfo@meditours.hu', 'info@banusztanya.hu', 'info@cottonhouse.hu', 'matersal@invitel.hu', 'curran88@gofree.indigo.ie', 'nyugatifogado@t-email.hu', 'familiatanya@freemail.hu', 'borgoncbt@vnet.hu', 'info@haromhattyu.hu', 'dunagaz@dunagaz.hu', 'villamedici@villamedici.hu', 'bwhungaria.booking@danubiushotels.com', 'kulcsar.imre@axelero.hu', 'bulrock@abv.bg', 'mail@klaegager.dk', 'helyfoglalas@javorkut.hu', 'contact@lecrillon-luberon.com', 'margit.johannsen@privat.dk', 'info@taevaskoja.ee', 'info@csilleberciszabadido.hu', 'guth@nyirerdo.hu', 'amb@bbsyd.dk', 'gemenc@freemail.hu', 'office@st-george-bg.info', 'tapolcafogado@chello.hu', 'rositsapamporovo@abv.bg', 'hmagistern@gmail.com', 'info@waide.ee', 'ingaes@live.dk', 'kontakt@blomsterly.dk', 'bb@harbogaarde.dk', 'theoldchestnuttree@me.com', 'info@aah-ja.dk', 'hede@skagennet.dk',];

		$qb = $this->rentalDao->createQueryBuilder('r');
		$qb->where($qb->expr()->in('r.email', $emails))
			->andWhere($qb->expr()->gte('r.id', ':minId'))->setParameter('minId', 25669);

		$rentals = $qb->getQuery()->getResult();


		/** @var $discarder \Tralandia\Rental\Discarder */
		$discarder = $this->getContext()->getByType('Tralandia\Rental\Discarder');

		/** @var $rental \Entity\Rental\Rental */
		foreach($rentals as $rental) {
			$user = $rental->getUser();
			$discarder->discard($rental);

			$this->userDao->delete($user);
			break;
		}
	}


	public function actionImportImages($limit = 10)
	{
		/** @var $rm \Image\RentalImageManager */
		$rm = $this->getContext()->getByType('\Image\RentalImageManager');

		$query = $this->em->getConnection()->query('select r.id as r, i.id as i
from rental r
left join rental_image as i on i.rental_id = r.id
where r.harvested = 1 and i.id is null
group by r.id limit ' . $limit);

		$rental = $query->fetchAll();

		$allImages = require_once(__DIR__ . '/images.php');

		foreach($rental as $rentalData) {

			/** @var $rental \Entity\Rental\Rental */
			$rental =$this->rentalDao->find($rentalData['r']);

			$images = Arrays::get($allImages, $rental->getId(), NULL);
			if(!$images) continue;

			$images = Strings::matchAll($images, '/https?\:\/\/[^\" ]+/i');

			$i = 1;
			foreach ($images as $path) {
				try {
					$path = $path[0];
					$image = $rm->saveFromFile($path);
					$this->em->persist($rental->addImage($image));
					if($i == 10) break;
					$i++;
				} catch(UnknownImageFileException $e) {
					continue;
				}
			}

			$this->em->persist($rental);
			$this->em->flush();
		}
	}


	public function actionClearDescription($limit = 10, $chunk = 0)
	{
		$editLogQb = $this->em->getRepository(EDIT_LOG_ENTITY)->createQueryBuilder('e');

		$editLogQb->select('IDENTITY(e.rental) AS rId')->groupBy('e.rental');

		$editedRentals = $editLogQb->getQuery()->getScalarResult();

		$editedRentalsIds = [];
		foreach($editedRentals as $editedRental) {
			$editedRentalsIds[] = $editedRental['rId'];
		}

		$rentalsQb = $this->rentalDao->createQueryBuilder('r');
		$rentalsQb->where($rentalsQb->expr()->notIn('r.id', $editedRentalsIds))
			->andWhere($rentalsQb->expr()->eq('r.harvested', ':harvested'))->setParameter('harvested', TRUE)
			->setMaxResults($limit)
			->setFirstResult($chunk * $limit);

		$rentals = $rentalsQb->getQuery()->getResult();

		/** @var $rental \Entity\Rental\Rental */
		foreach($rentals as $rental) {
			$firstAnswer = $rental->getFirstInterviewAnswer();
			$phrase = $firstAnswer->getAnswer();
			foreach($phrase->getTranslations() as $translation) {
				$translation->setTranslation('');
			}
		}

		$this->em->flush();
	}


	public function actionBuildCacheForMapSearch($chunk = 0, $limit = 1000)
	{
		$start = time();
		$qb = $this->rentalDao->createQueryBuilder('r');

		$qb = $this->rentals->filterRentalsForMap($qb);

		$qb->setMaxResults($limit);
		$qb->setFirstResult($chunk * $limit);

		$this->rentals->getRentalsForMap($qb, $this);

		$this->payload->start = $start;
		$this->payload->duration = time() - $start;
		$this->sendPayload();
	}

}

