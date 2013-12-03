<?php

namespace AdminModule;


use Doctrine\ORM\QueryBuilder;
use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Entity\User\Role;
use Environment\Environment;
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
	 * @var \Transliterator
	 */
	protected $transliterator;

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

			$newSlug = $this->transliterator->transliterate($name);
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
		$emails = ['apartamentytatry@gmail.com','budzu@wp.pl','eva@ecom.hu','info@laetitia.hu','iwona.proniewicz@jordan.pl','reservations@goldentulipcentralmolitor.com','Willapodreglami@interia.pl','a.kolobrzeg.pl@wp.pl','abra@abra.kolobrzeg.pl','abramczyk@wp.pl','adam.leba@vp.pl','adamar@adamar-pensjonat.pl','agat@ustron.pl','agro@go2.pl','agroletnica@op.pl','agropark@op.pl','agrorogalscy@interia.pl','agroturystyka@ubaltazara.pl','akacja@kudowa.pl','alexnowa@wp.pl','amarek120@wp.pl','ambrozja@ta.pl','amestr@wp.eu','andrzej.majkowski3@neostrada.pl','andystan69@onet.pl','angela@allikavilla.ee','angela@leba.pl','antoni-p@gmx.net','apartament@morskirelaks.pl','apartamenty.kontakt@op.pl','apartamenty@malachit.info','apollak@poczta.onet.pl','apollo@hotelapollo.pl','aquamontana@gmail.com','aranykereszt5700@t-online.hu','aranypan@t-email.hu','arci0802@wp.pl','aris@3sh.be','asiamordon@interia.eu','asosnowski13@wp.pl','atlasrezerwacje@gmail.com','attilahotel@t-online.hu','azbm@wp.pl','baltic78@post.pl','banot@poczta.fm','basia.zelek@neostrada.pl','beskid@beskid.pl','bialawiselka@zajazdpolski.com.pl','bionika@bionika.pl','biuro@bernadeta.eu','biuro@chatatoniego.pl','biuro@chatawgorach.pl','biuro@czardasz.pl','biuro@fanaberiabrenna.pl','biuro@kopernik.alpha.pl','biuro@kukle.pl','biuro@starygron.pl','biuro@trzy-deby.pl','biuro@zloty-brzeg.pl','booking@hostel70s.com','bukowina@domzwitrazami.pl','bursztyn@ta.pl','butterfly-factory@home.pl','campingmako@freemail.hu','carlton@pt.lu','cebulska.j@op.pl','cetniewo@o2.pl','chemik@sanatoriumchemik.pl','collectionmalak@onet.eu','contact@grettisborg.is','contact@hotelcravat.lu','corvinhotel@t-online.hu','dafne.ciechocinek@wp.pl','daniel795@onet.eu','danka.jarzynska@gmail.com','darlowkoagnieszka@wp.pl','domagat@domagat.home.pl','domek.cioci@gmail.com','domek.skulsk@wp.pl','domek13@op.pl','dommorze@wp.pl','domotel@domotel.tm.pl','dom_gosi@interia.pl','dorkaborka@t-online.hu','dowmunt@wp.pl','dragon.corp@interia.pl','dwborowik@poczta.onet.pl','dwormazurski@o2.pl','eden@eden-ciechocinek.pl','edenlac@pt.lu','edmundpiotrowski@o2.pl','edward.rosik@wp.pl','ekowczasy@poczta.onet.pl','engelenaandewaterkant@skynet.be','enquiries@arnoldshotel.com','epodhajska@poczta.onet.pl','europa_pl@onet.eu','ewa-jakielaszek@wp.pl','ewa.holek@wp.pl','florapanzio@freemail.hu','fwrar@poczta.fm','gimipanzio@freemail.hu','gisting@leopold.is','gorgol9@wp.pl','gosciniec.graf@gmail.com','gosia@ranczonadmorzem.pl','granatowka@wp.pl','grandhot@pt.lu','grundarfjordur@hostel.is','guesthousereykjavik@guesthousereykjavik.com','hannaherbert@wp.pl','he@he.pl','hevizhaz@t-online.hu','hodorowicz@neostrada.pl','holt@holt.is','hostel24@hostel24.com.pl','hostelcentrum@o2.pl','hostelcinema@o2.pl','hotel@dezalm.be','hotel@dooleys-hotel.ie','hotel@karmel.com.pl','hotel@l-do-rado.com','hotel@madridbg.com','hotel@mistia.org.pl','hotel@odr.net.pl','hotel@ulanspa.pl','hotel@uwitaszka.pl','hotelartur@hotelartur.pl','hotelatena@onet.pl','hotelborg@hotelborg.is','hotelotomin@home.pl','hotelovit@t-online.hu','hotelszentjanos@hotelszentjanos.hu','hotelura@realnet.pl','hotel_atlantic@abv.bg','hubertuscsongrad@gmail.com','huszarpanzio@freemail.hu','i.tomket@gmx.de','ilonacekcyn@wp.pl','info@aadutalu.ee','info@actica-apart.wroclaw.pl','info@agroturystyka-arkadia.pl','info@alfa-inn.com','info@altmoisa.ee','info@angriananhotel.com','info@antigonehotel.be','info@apartament-wisla.com.pl','info@apartmenthouse.is','info@aphroditehotel.hu','info@astoria-antwerp.com','info@astoria.be','info@bakkegaard.eu','info@bb44.is','info@bor-bazilika-panzio.hu','info@burowianka.pl','info@bursztyn-kolobrzeg.pl','info@chateau-urspelt.lu','info@coque.lu','info@denderhof.be','info@derrynane.com','info@diosgyorivarkert.hu','info@downingsbayhotel.com','info@eskapada.net.pl','info@greenhostel.wroclaw.pl','info@gtex.hu','info@hajduhotel.hu','info@heltermaahotell.ee','info@hillhostel.pl','info@holiday-hungary.hu','info@hotel-basilique.lu','info@hotelalbert.be','info@hotelbolero.hu','info@hotelducommerce.lu','info@hotelfron.is','info@hotelinter.lu','info@hotelsilvanus.hu','info@hoteltuilerieen.com','info@hotelvecupe.lv','info@hotelzeta.hu','info@jaskinia.org.pl','info@kehidavendeghaz.hu','info@kincsemhotel.hu','info@kincsesvendeghaz.hu','info@liva.lv','info@malomerdo.hu','info@nad-potokiem.pl','info@nyergeshotel.hu','info@pensjonatavalon.com','info@pensjonatmalgosia.pl','info@pilguse.ee','info@piwna.gda.pl','info@reykjavik-apartments.com','info@szegediapartman.hu','info@teatrum.hu','info@termalschmitz.com','info@trzykafki.pl','info@varvendeglo.hu','info@victorhugo.bestwestern.de','info@villanegra-panzio.hu','info@vwmuseum.pl','infobis@hotel-fortunabis.pl','izalew77@tlen.pl','jaddan1@wp.pl','jamna@jamna.agro.pl','jaszczurowka@smacznego.com.pl','jedrusiowa.polana@poczta.fm','jezioronarty@republika.pl','joasia@joasia.com.pl','jolanta@wisla.pl','jubilat12@wp.pl','jurekzie@poczta.onet.pl','kakaris@wp.pl','kamilzbikowski1@wp.pl','karolina.puzio@wp.pl','kastelyhotel@t-online.hu','kisinoc@invitel.hu','klapytowka@gmail.com','konferencje@konstancja.net.pl','kontakt@admiral-jastarnia.pl','kontakt@nocleg-gdy.pl','kontakt@pokoje-bydgoszcz.pl','kruszyna@kruszyna.com.pl','krzysztoftrzop@wp.pl','kwatery.fado@gmail.com','lailasbb@hotmail.com','lajs@lajs.com.pl','latarniaprzymorska@wp.pl','leba1117@wp.pl','lesne.zacisze@wp.pl','letnisko@gmail.com','lipiec.zbigniew@neostrada.pl','lomzahotel@gromada.pl','lubon@poczta.onet.eu','luciasa@o2.pl','mail@cityhotel.lu','mail@svanfolk-bb.dk','mail@villaanna.lv','maniowka@suwalki-turystyka.info.pl','marga33@poczta.fm','margaret@morze.net.pl','marketing@bobrowadolina.com','marketing@hotelpiaskowy.pl','marketing@kaposhotel.hu','marketing@kotarz.com','markusrabka@onet.pl','maupil@wp.pl','mazurskisen@gmail.com','miszkiel_23a@o2.pl','mjkwiat@wp.pl','modrok@modrok.pl','mogyoroapartman@citromail.hu','molohotel@balatonihajozas.hu','mosir.walcz@hot.pl','moulinas@pt.lu','mozsolicsm@keszthelynet.hu','murzasichle@poczta.onet.pl','nemilio77@gmail.com','newton@simnet.is','noclegilublin@wp.pl','nowita.zakopane@wp.pl','odinsve@hotelodinsve.is','office@fusionhostel.pl','osrodek@polonina.com.pl','pallas@pallas.ee','palyiica@vipmail.hu','parizsiudvar@freemail.hu','pensjonat-pod-roza@wp.pl','pensjonat3@op.pl','perkoz@zhp.pl','perla@ta.pl','petro.kalman@upcmail.hu','piotr.zachwieja@wp.pl','piotrbuszko@wp.pl','piotruspan@ta.pl','poczta@dwanna.pl','podorzechem18@wp.pl','podwierchem@op.pl','pokoje-u-gabi@o2.pl','pokoje@jabo.com.pl','postmaster@ertank.axelero.net','prima@bestwestern-prima.pl','przemyslaw@hotmail.com','radekvendeghaz@gmail.com','raidosaber@wp.pl','rajmund.w@op.pl','ranczoujana@europoczta.pl','recepcja@astoria-mielno.pl','recepcja@atriumhotel.pl','recepcja@ddf.nysa.pl','recepcja@domnapodwalu.pl','recepcja@europa-hotel.com.pl','recepcja@hotelbielany.com.pl','recepcja@hoteldarlowo.pl','recepcja@osrodekzar.pl','recepcja@ostaniec.com.pl','recepcja@pensjonatmax.pl','recepcja@rossija.com.pl','recepcja@szelment.pl','recepcja@willa-sandra.pl','recepcjaewunia@wp.pl','renataparadowska@o2.pl','reservation@hoteleurocap.com','reservation@hotelhelikon.hu','reserve@hotelszieszta.hu','rezerwacja@afekt.swinoujscie.pl','rezerwacja@babelhostel.pl','rezerwacja@divaspa.info','rezerwacja@hotelperla.com.pl','rezerwacja@pitstophostel.pl','rezerwacja@zloty-las.pl','rezerwacjakw@gmail.com','rezerwacje@hajduczek.com.pl','rezerwacje@hotel-kontrast.pl','rezerwacje@jaworowachata.pl','rezerwacje@tatrzanskie-turnie.pl','rownica@brenna.pl','royalpanzio@royalpanzio.hu','sagadi.hotell@rmk.ee','stadninazarnowo@interia.pl','stanislaw.kulach@neostrada.pl','stara.jablon@onet.pl','stas@zapotokiem.idl.pl','swojskachata@o2.pl','szczypin@onet.eu','szescdebow@prusewo.pl','tabun@kuznia.net','tadeusz.stasiak58@wp.pl','tarsoly.zsuzsa@freemail.hu','teresa-sopot@o2.pl','thermenhotel@stoiser.com','timarhazpanzio@gmail.com','tomek7@poczta.onet.pl','torun.hostel@gmail.com','trotowka@op.pl','turulpanzio@t-email.hu','turystyka@oswiata.slask.pl','ukazika@orawka.pl','ustka@apartamentyzklimatem.pl','ustkaskaut@op.pl','uzofii@poczta.fm','var-park-panzio@freemail.hu','vasianda@gmail.com','vehendi@hot.ee','walpusz@poczta.onet.pl','warsaw@ihg.com','warszawahotel.airport@gromada.pl','wczasy@airen.pl','wdowiarek@ustkawczasy.pl','wierakm@wp.pl','willa@evelvet.pl','willajedrus1@wp.pl','willakrystyny@wp.pl','witek_roman@o2.pl','witow@o2.pl','wredonis@o2.pl','xterne@gazeta.pl','zajazdwika@onet.eu','zakopane@autograf.pl','zdzislawlew@op.pl','zielkamien@wp.pl','zolta_pokoje@interia.pl'];

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

}

