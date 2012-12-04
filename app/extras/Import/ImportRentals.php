<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Dictionary as D,
	Service as S,
	Service\Log as SLog;

class ImportRentals extends BaseImport {

	public function doImport($subsection = NULL) {
		//\Extras\Models\Service::flush(TRUE);

		$context = $this->context;
		$model = $this->model;

		// Detaching all media
		//qNew('update medium set rental_id = NULL where rental_id > 0');

		$nameDictionaryType = $this->createPhraseType('\Rental\Rental', 'name', 'NATIVE', array('checkingRequired' => TRUE));
		$briefDescriptionDictionaryType = $this->createPhraseType('\Rental\Rental', 'briefDescription', 'NATIVE', array('checkingRequired' => TRUE));
		$descriptionDictionaryType = $this->createPhraseType('\Rental\Rental', 'description', 'NATIVE', array('checkingRequired' => TRUE));
		$teaserDictionaryType = $this->createPhraseType('\Rental\Rental', 'teaser', 'NATIVE', array('checkingRequired' => TRUE));
		$interviewQuestionPhraseType = $this->createPhraseType('\Rental\InterviewQuestion', 'question');

		$model->flush();


		$r = q('select * from objects_types_new where trax_en_type_id > 0');
		while($x = mysql_fetch_array($r)) {
			$oldRentalTypesEn[$x['id']] = $x['trax_en_type_id'];
		}

		$locationTypes = array();
		$locationTypes['country'] = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('country');
		$locationTypes['continent'] = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('continent');
		$locationTypes['region'] = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('region');
		$locationTypes['administrativeregionlevelone'] = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('administrativeregionlevelone');
		$locationTypes['administrativeregionleveltwo'] = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('administrativeregionleveltwo');
		$locationTypes['locality'] = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('locality');

		$interviewQuestions = array();
		foreach ($context->rentalInterviewQuestionRepositoryAccessor->get()->findAll() as $key => $value) {
			$interviewQuestions[$value->oldId] = $value;
		}

		$languages = array();
		foreach ($context->languageRepositoryAccessor->get()->findAll() as $key => $value) {
			$languages[$value->oldId] = $value;
		}

		$ownerRole = $context->userRoleRepositoryAccessor->get()->findOneBySlug('owner');

		$en = $context->languageRepositoryAccessor->get()->findOneByIso('en');
		$now = time();

		if ($this->developmentMode == TRUE) {
			$r = q('select * from objects where country_id = 46 order by rand() limit 150');
		} else {
			$r = q('select * from objects');
		}

		while ($x = mysql_fetch_array($r)) {
			if($context->rentalRepositoryAccessor->get()->findOneByOldId($x['id'])) {
				continue;
			}
			$rental = $context->rentalEntityFactory->create();
			$rental->oldId = $x['id'];

			$user = $context->userRepositoryAccessor->get()->findOneByLogin(qc('select email from members where id = '.$x['member_id']));
			if (!$user) {
				continue; // @todo dorobit
				$user = qNew('select id from user where isOwner = 1 and oldId = '.$x['member_id']);
				$user = mysql_fetch_array($user);
				if ($user['id'] > 0) {
					$user = $context->userRepositoryAccessor->get()->find($user['id']);
				} else {
					//@todo - treba zapisat do logov, ze sa nenasiel user, ktory sa hladal, docasne sa to ignoruje...
					continue;
				}
			}
			$rental->user = $user;

			$rental->editLanguage = $context->languageRepositoryAccessor->get()->findOneByOldId($x['edit_language_id']);

			$rental->status = $x['live'] == 1 ? $rental::STATUS_LIVE : $rental::STATUS_DRAFT;
			$oldRentalType = current(explode(',,', substr($x['objects_types_new'], 2, -2)));
			$rental->setType($context->rentalTypeRepositoryAccessor->get()->findOneByOldId($oldRentalTypesEn[$oldRentalType]));

			// Locations
			$rental->setPrimaryLocation($context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $x['country_id'], 'type' => $locationTypes['country'])));

			$administrativeRegion = $context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $x['region_admin_id'], 'type' => $locationTypes['administrativeregionlevelone']));
			if (!$administrativeRegion) {
				$administrativeRegion = $context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $x['region_admin_id'], 'type' => $locationTypes['administrativeregionleveltwo']));
			}

			if ($administrativeRegion) {
				$rental->addLocation($administrativeRegion);
			}

			$regions = array_unique(array_filter(explode(',', $x['regions'])));
			if (is_array($regions) && count($regions)) {
				foreach ($regions as $key => $value) {
					$temp = $context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $value, 'type' => $locationTypes['region']));
					if ($temp) $rental->addLocation($temp);
				}
			}

			// @todo dorobit
			// d(array('oldId' => $x['locality_id'], 'type' => $locationTypes['locality']));
			// $locationTemp = $context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $x['locality_id'], 'type' => $locationTypes['locality']));
			// if($locationTemp) $rental->addLocation($locationTemp);

			// $thisLocality = $context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $x['locality_id'], 'type' => $locationTypes['locality']));

			// $thisNamePhrase = $context->phraseServiceFactory->create($thisLocality->name);
			// $thisCountry = $thisLocality->parent;
			// $thisCountryNamePhrase = $context->phraseServiceFactory->create($thisCountry->name);
			// $rental->address = new \Extras\Types\Address(array(
			// 	'address' => array_filter(array($x['address'])),
			// 	'postcode' => $x['post_code'],
			// 	'locality' => $thisNamePhrase->getTranslation($thisCountry->defaultLanguage)->translation,
			// 	'sublocality' => $x['sublocality'],
			// 	'country' => $thisCountryNamePhrase->getTranslation($en)->translation,
			// ));

			$rental->latitude = new \Extras\Types\Latlong($x['latitude']);
			$rental->longitude = new \Extras\Types\Latlong($x['longitude']);

			$rental->slug = $x['name_url'];
			$rental->name = $this->createPhraseFromString('\Rental\Rental', 'name', 'NATIVE', $x['name'], $rental->editLanguage);
			
			$rental->teaser = $this->createNewPhrase($teaserDictionaryType, $x['marketing_dic_id']);


			// Contacts
			$contacts = new \Extras\Types\Contacts();

			$contacts->add(new \Extras\Types\Name('', '', $x['contact_name']));

			$x['contact_phone'] = @unserialize(stripslashes($x['contact_phone']));
			if (is_array($x['contact_phone'])) {
				foreach ($x['contact_phone'] as $key => $value) {
					$contacts->add(new \Extras\Types\Phone(implode('', $value)));
				}
			}

			$contacts->add(new \Extras\Types\Email($x['contact_email']));
			$contacts->add(new \Extras\Types\Skype($x['contact_skype']));
			if (\Nette\Utils\Validators::isUrl($x['contact_url'])) $contacts->add(new \Extras\Types\Url($x['contact_url']));
			if (\Nette\Utils\Validators::isUrl($x['url'])) $contacts->add(new \Extras\Types\Url($x['url']));

			$rental->contacts = $contacts;

			$spokenLanguages = array_unique(array_filter(explode(',', $x['languages_spoken'])));
			if (is_array($spokenLanguages) && count($spokenLanguages)) {
				foreach ($spokenLanguages as $key => $value) {
					$rental->addSpokenLanguage($context->languageRepositoryAccessor->get()->findOneByOldId($value));
				}
			}

			// Amenities
			$allAmenities = array();
			$r1 = qNew('select * from rental_amenity');
			while ($x1 = mysql_fetch_array($r1)) {
				$allAmenities[$x1['oldId']] = $context->rentalAmenityRepositoryAccessor->get()->find($x1['id']);
			}

			// Activities
			$temp = array_unique(array_filter(explode(',,', $x['activities'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Food
			$temp = array_unique(array_filter(explode(',,', $x['food'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Owner
			$temp = array_unique(array_filter(explode(',,', $x['owner'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Amentities General
			$temp = array_unique(array_filter(explode(',,', $x['amenities_general'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Amenities Congress
			$temp = array_unique(array_filter(explode(',,', $x['amenities_congress'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Amenities Wellness
			$temp = array_unique(array_filter(explode(',,', $x['amenities_wellness'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Tags
			$allTags = array();
			$r2 = qNew('select * from rental_tag');
			while ($x1 = mysql_fetch_array($r2)) {
				$allTags[$x1['oldId']] = $context->rentalTagRepositoryAccessor->get()->find($x1['id']);
			}
			$temp = array_unique(array_filter(explode(',,', $x['tags'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allTags[$value])) $rental->addTag($allTags[$value]);
				}
			}

			// Interview
			$temp = unserialize(stripslashes($x['interview']));
			if (is_array($temp) && count($temp)) {

				$answersNew = array();
				foreach ($temp as $oldLanguageId => $answers) {
					foreach ($answers as $oldQuestionId => $answer) {
						$answersNew[$oldQuestionId][$oldLanguageId] = $answer;
					}
				}

				foreach ($answersNew as $oldQuestionId => $answers) {

					$answerEntity = $context->rentalInterviewAnswerRepositoryAccessor->get()->createNew();
					$answerEntity->setQuestion($interviewQuestions[$oldQuestionId]);

					$answerPhrase = $this->createNewPhrase($interviewQuestionPhraseType);
					$answerPhrase = $context->phraseDecoratorFactory->create($answerPhrase);
					foreach ($answers as $oldLanguageId => $value) {
						if (!strlen($value)) continue;

						$language = $languages[$oldLanguageId];
						$translation = $answerPhrase->getTranslation($language);
						$translation->translation = $value;
					}

					$answerEntity->answer = $answerPhrase->getEntity();
					$rental->addInterviewAnswer($answerEntity);
				}

			}

			if (strlen($x['check_in'])) {
				$rental->checkIn = $x['check_in'];
			}

			if (strlen($x['check_out'])) {
				$rental->checkOut = $x['check_out'];
			}

			if (strlen($x['rooms'])) {
				$rental->rooms = $x['rooms'];
			}

			if (strlen($x['price_season'])) {
				$rental->priceSeason = $x['price_season'];
			}

			if (strlen($x['price_offseason'])) {
				$rental->priceOffSeason = $x['price_offseason'];
			}

			if (strlen($x['capacity_max'])) {
				$rental->maxCapacity = $x['capacity_max'];
			}


			// Pricelist
			$pricelists = array();
			$temp = unserialize(stripslashes($x['prices_simple']));
			if (is_array($temp) && count($temp)) {
				$pricelists['simple'] = $temp;
			}

			$temp = unserialize(stripslashes($x['prices_advanced']));
			if (is_array($temp) && count($temp)) {
				$pricelists['advanced'] = $temp;
			}

			$temp = unserialize(stripslashes($x['prices_upload']));
			if (is_array($temp) && count($temp)) {
				$pricelists['upload'] = $temp;
			}

			$rental->pricelists = $pricelists;
			$model->persist($rental);

			// Media
			$temp = array_unique(array_filter(explode(',', $x['photos'])));
			if (is_array($temp) && count($temp)) {
				if ($this->developmentMode == TRUE) $temp = array_slice($temp, 0, 6);
				foreach ($temp as $key => $value) {
					$medium = $context->mediumRepositoryAccessor->get()->findOneByOldUrl('http://www.tralandia.com/u/'.$value);
					if (!$medium) {
						$mediumEntity = $context->mediumRepositoryAccessor->get()->createNew();
						$medium = $context->mediumDecoratorFactory->create($mediumEntity);
						$medium->setContentFromUrl('http://www.tralandia.com/u/'.$value);
						$rental->addMedium($medium->getEntity());
					} else {
						$rental->addMedium($medium);
					}
				}
			}
		
			// Calendar
			$temp = array_unique(array_filter(explode(',', $x['calendar'])));

			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if ($value < $now) continue;
					$temp[$key] = date('dmy', $value);
				}
			}
			$rental->calendar = implode(',', $temp);

			$rental->calendarUpdated = fromStamp($x['calendar_updated']);

			$rental->created = fromStamp($x['date_added']);
			$model->persist($rental);

			//@todo
			//$rental->rank = $rental->calculateRank(); // urobit tuto fciu
		}
		$model->flush();
	}

}