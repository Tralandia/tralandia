<?php

namespace AdminModule;


use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Types\Json;
use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Entity\Routing\PathSegment;
use Entity\User\Role;
use Environment\Environment;
use Nette\Application\Responses\TextResponse;
use Nette\UnknownImageFileException;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;
use Tralandia\BaseDao;

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
	 * @var \Tralandia\SearchCache\InvalidateRentalListener
	 */
	protected $invalidateRentalListener;

	/**
	 * @autowire
	 * @var \Service\Rental\RentalSearchService
	 */
	protected $rentalSearch;

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

		$this->rentalSearch->setLocationCriterion($location);

		$qb = $this->rentalDao->createQueryBuilder('r');
		$qb->select('r.id')
			->leftJoin('r.address', 'a')
			->leftJoin('a.locations', 'l')
			->where($qb->expr()->eq('l.id', ':location'))->setParameter('location', $location);

		$rentalsIds = $qb->getQuery()->getResult();

		$rentalsIds = \Tools::arrayMap($rentalsIds, 'id');

		$this->polygonCalculator->setRentalsForLocation($location);

		$this->em->flush();

		$qb = $this->rentalDao->createQueryBuilder('r');
		$qb->leftJoin('r.address', 'a')
			->leftJoin('a.locations', 'l')
			->where($qb->expr()->eq('l.id', ':location'))->setParameter('location', $location)
			->andWhere($qb->expr()->notIn('r.id', $rentalsIds));

		$rentals = $qb->getQuery()->getResult();

		foreach($rentals as $rental) {
			$this->invalidateRentalListener->onSuccess($rental);
		}


		$this->sendJson(['success' => true]);
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


	public function actionCreateMissingAnswers($limit = 100)
	{
		/** @var $questionDao BaseDao */
		$questionDao = $this->em->getRepository(INTERVIEW_QUESTION_ENTITY);
		/** @var $answersDao BaseDao */
		$answersDao = $this->em->getRepository(INTERVIEW_ANSWER_ENTITY);
		/** @var $lm \LeanMapper\Connection */
		$lm = $this->getContext()->getByType('\LeanMapper\Connection');

		$allQuestion = [];
		foreach($questionDao->findAll() as $question) {
			$allQuestion[$question->getId()] = $question;
		}

		$query = 'select y.rid, group_concat(y.qid) qids
from (select r.id rid, q.id qid from rental r, rental_interviewquestion q ) y
left join rental_interviewanswer a on a.rental_id = y.rid and a.question_id = y.qid
where a.id is null
group by y.rid
limit ' . $limit;
		$result = $lm->query($query);

		$rentalsIds = [];
		$missingQuestionsIds = [];
		foreach($result as $row) {
			$rentalId = $row->rid;
			$missingQuestionsIds[$rentalId] = explode(',', $row->qids);
			$rentalsIds[] = $rentalId;
		}

		unset($result);

		$rentals = $this->rentalDao->findBy(['id' => $rentalsIds]);

		$questionsCount = 0;
		/** @var $rental \Entity\Rental\Rental */
		foreach($rentals as $rental) {
			$rentalId = $rental->getId();
			foreach($missingQuestionsIds[$rentalId] as $missingQuestionId) {
				$answer = $answersDao->createNew();
				$answer->getAnswer()->setSourceLanguage($rental->getPrimaryLocation()->getDefaultLanguage());
				$answer->setQuestion($allQuestion[$missingQuestionId]);
				$rental->addInterviewAnswer($answer);

				$this->em->persist($answer);
				$questionsCount++;
			}
		}
		$this->em->flush();

		$this->sendResponse(new TextResponse("Added $questionsCount questions to ". count($rentals) . ' rentals'));
	}


	public function actionNewRentalType()
	{
		$name = 'bed & breakfast';
		$namePlural = $name;
		$slug = 'bed-and-breakfast';

		/** @var $rentalType \Entity\Rental\Type */
		$rentalType = $this->em->getRepository(RENTAL_TYPE_ENTITY)->createNew();
		$rentalType->setSlug($slug);
		$rentalType->getName()->getCentralTranslation()->updateVariations([
			[[\Entity\Language::NOMINATIVE => $name]],
			[[\Entity\Language::NOMINATIVE => $namePlural]],
		]);

		$this->em->persist($rentalType);
		$this->em->flush();

		$languageList = $this->languageDao->findBySupported(TRUE);

		$pathSegmentDao = $this->em->getRepository(PATH_SEGMENT_ENTITY);
		foreach ($languageList as $language) {
			$entity = $pathSegmentDao->createNew();
			$entity->primaryLocation = NULL;
			$entity->language = $language;
			$entity->pathSegment = $this->translateRentalType($rentalType->getName(), $language, 1, 0, 'nominative');
			$entity->type = PathSegment::RENTAL_TYPE;
			$entity->entityId = $rentalType->getId();

			$this->em->persist($entity);
		}

		$this->em->flush();

		$this->sendPayload();
	}


	protected function translateRentalType($phrase, $language, $plural = NULL, $gender = NULL, $case = NULL)
	{
		$text = $this->translate($phrase, null, [
			\Tralandia\Localization\Translator::VARIATION_PLURAL => $plural,
			\Tralandia\Localization\Translator::VARIATION_GENDER => $gender,
			\Tralandia\Localization\Translator::VARIATION_CASE => $case
		], null, $language);

		return Strings::webalize($text);
	}

	public function actionCreatePhraseForRentalDescription($limit = 100)
	{
		$query = 'SELECT r FROM \Entity\Rental\Rental r WHERE r.description IS NULL';
		$query = $this->rentalDao->createQuery($query);
		$query->setMaxResults($limit);


		$phraseCreator = new \Service\Phrase\PhraseCreator($this->em);
		/** @var $rental \Entity\Rental\Rental */
		foreach($query->getResult() as $rental) {
			$phraseTypeName = '\Entity\Rental\Rental:description';
			$rental->setDescription($phraseCreator->create($phraseTypeName));
		}

		$this->em->flush();

		$this->payload->success = true;
		$this->payload->time = time();
		$this->sendPayload();
	}


	public function actionFillPriceForTable()
	{
		$combinations = [
			[645219, NULL, 13],
			[645219, 645271, 1],
			[645219, 645272, 11],
			[645219, 645273, 12],
			[645220, 645271, 3],
			[645220, 645272, 43],
			[645220, 645273, 46],
			[645221, 645271, 8],
			[645221, 645272, 44],
			[645221, 645273, 45],
			[645222, 645271, 2],
			[645222, 645272, 14],
			[645222, 645291, 15],
			[645222, 645273, 16],
			[645223, 645271, 7],
			[645223, 645272, 17],
			[645226, 645271, 18],
			[645227, 645271, 9],
			[645228, 645271, 10],
			[645229, 645271, 19],
			[645230, 645271, 41],
			[645231, 645271, 42],
			[645232, 645271, 28],
			[645233, 645271, 29],
			[645234, 645271, 30],
			[645235, 645271, 31],
			[645237, 645271, 32],
			[645242, 645271, 33],
			[645243, 645271, 36],
			[645244, 645271, 37],
			[645245, 645271, 38],
			[645246, 645271, 39],
			[645247, 645271, 40],
			[645224, 645219, 35],
			[645224, 645271, 20],
			[645224, 645273, 47],
			[645225, 645271, 34],
			[645248, NULL, 21],
			[645249, NULL, 22],
			[645250, NULL, 23],
			[645251, NULL, 24],
			[645252, NULL, 25],
			[645269, 645271, 26],
			[645254, NULL, 27],
			[645255, 645271, 4],
			[645255, 645272, 48],
			[645253, NULL, 5],
			[645270, NULL, 6],
		];

		$phrasesIds = [];
		foreach($combinations as $value) {
			$phrasesIds[] = $value[0];
			$phrasesIds[] = $value[1];
		}

		$phrasesIds = array_unique($phrasesIds);
		$phrases = $this->em->getRepository(PHRASE_ENTITY)->findBy(['id' => $phrasesIds]);

		$phrasesById = [];
		foreach($phrases as $phrase) {
			$phrasesById[$phrase->id] = $phrase;
		}

		$priceForDao = $this->em->getRepository(RENTAL_PRICE_FOR_ENTITY);
		$i = 0;
		foreach($combinations as $value) {
			/** @var $priceFor \Entity\Rental\PriceFor */
			$priceFor = $priceForDao->createNew();
			$priceFor->setFirstPart($phrasesById[$value[0]]);
			$value[1] && $priceFor->setSecondPart($phrasesById[$value[1]]);
			$priceFor->setOldId($value[2]);
			$priceFor->setSort($i);

			$this->em->persist($priceFor);
			$i++;
		}
		$this->em->flush();
	}

}

