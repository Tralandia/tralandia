<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\DateTime,
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
		$locationTypes['locality'] = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('locality');
		
		$roomTypes = array();
		$roomTypes[1] = $context->rentalRoomTypeRepositoryAccessor->get()->findOneBySlug('building');
		$roomTypes[2] = $context->rentalRoomTypeRepositoryAccessor->get()->findOneBySlug('apartment');
		$roomTypes[3] = $context->rentalRoomTypeRepositoryAccessor->get()->findOneBySlug('room');

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
			//$r = q('select * from objects where id = 7116');
			//$r = q('select * from objects where country_id = 46 order by rand() limit 3');
			$existingIds = array();
			if (count($existingIds)) {

			} else {

			}
			$r = q('select * from objects where country_id = 46 order by id');
		} else {
			$existingIds = array();
			if (count($existingIds)) {
				$r = q('select * from objects where id id > '.$this->savedVariables['lastRentalImported'].' order by id asc limit 500');
			} else {
				$r = q('select * from objects order by id asc limit 500');
			}
		}

		while ($x = mysql_fetch_array($r)) {
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
			
			if (isset($oldRentalTypesEn[$oldRentalType])) {
				$rental->setType($context->rentalTypeRepositoryAccessor->get()->findOneByOldId($oldRentalTypesEn[$oldRentalType]));
			}

			// Address
			$address = $context->contactAddressRepositoryAccessor->get()->createNew(FALSE);
			$address->status = \Entity\Contact\Address::STATUS_UNCHECKED;

			$address->address = implode('\n', array_filter(array($x['address'])));
			$address->subLocality = $x['sublocality'];
			$address->postalCode = $x['post_code'];
			$address->primaryLocation = $context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $x['country_id'], 'type' => $locationTypes['country']));
			$address->locality = $context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $x['locality_id'], 'type' => $locationTypes['locality']));
			if ($x['latitude'] != 0 && $x['longitude'] != 0) {
				$gps = new \Extras\Types\Latlong($x['latitude'], $x['longitude']);
				$address->setGps($gps);
			}
			if ($x['locality_status'] == 'OK') {
				$address->status = \Entity\Contact\Address::STATUS_OK;
			} else if ($x['locality_status'] == 'localityNotFound') {
				$address->status = \Entity\Contact\Address::STATUS_INCOMPLETE;
			} else if ($x['locality_status'] == 'outsideCountry') {
				$address->status = \Entity\Contact\Address::STATUS_MISPLACED;
			}

			$rental->address = $address;

			//$rental->primaryLocation = $context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $x['country_id'], 'type' => $locationTypes['country']));

			$rental->slug = $x['name_url'];
			$truncatedName = Strings::truncate($x['name'], 60);
			$rental->name = $this->createPhraseFromString('\Rental\Rental', 'name', 'NATIVE', $x['name'], $rental->editLanguage);
			
			$rental->teaser = $this->createNewPhrase($teaserDictionaryType, $x['marketing_dic_id'], NULL, NULL, $rental->editLanguage);

			// Contact Name
			$rental->contactName = $x['contact_name'];

			// Contact Phones
			$phones = explode(';', $x['phones']);
			foreach ($phones as $key => $value) {
				if (strlen($value)) {
					if ($tempPhone = $this->context->phoneBook->getOrCreate($value)) {
						$rental->setPhone($tempPhone);
						break; // Importujeme len prve cislo
					}	
				}
			}

			// Contact Email
			$rental->setEmail($context->contactEmailRepositoryAccessor->get()->createNew(FALSE)->setValue($x['contact_email']));

			// Contact Url
			if (\Nette\Utils\Validators::isUrl($x['contact_url'])) {
				$rental->setUrl($context->contactUrlRepositoryAccessor->get()->createNew(FALSE)->setValue($x['contact_url']));
			}
			if (!$rental->url && \Nette\Utils\Validators::isUrl($x['url'])) {
				$rental->setUrl($context->contactUrlRepositoryAccessor->get()->createNew(FALSE)->setValue($x['url']));
			}
			// Spoken Languages
			$spokenLanguages = array_unique(array_filter(explode(',', $x['languages_spoken'])));
			if (is_array($spokenLanguages) && count($spokenLanguages)) {
				foreach ($spokenLanguages as $key => $value) {
					$t = $context->languageRepositoryAccessor->get()->findOneByOldId($value);
					if ($t) $rental->addSpokenLanguage($t);
				}
			}

			// Amenities
			$allAmenities = array();
			$r1 = qNew('select * from rental_amenity');
			$specialAmenities = array('activity', 'food', 'owner-availability', 'congress', 'wellness');
			while ($x1 = mysql_fetch_array($r1)) {
				$thisAmenity = $context->rentalAmenityRepositoryAccessor->get()->find($x1['id']);
				if (in_array($thisAmenity->type->slug, $specialAmenities)) {
					$thisId = $thisAmenity->type->slug.'-'.$x1['oldId'];
				} else {				
					$thisId = $x1['oldId'];
				}
				$allAmenities[$thisId] = $thisAmenity;
			}

			// Activities
			$temp = array_unique(array_filter(explode(',,', $x['activities'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities['activity-'.$value])) $rental->addAmenity($allAmenities['activity-'.$value]);
				}
			}

			// Food
			$temp = array_unique(array_filter(explode(',,', $x['food'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities['food-'.$value])) $rental->addAmenity($allAmenities['food-'.$value]);
				}
			}

			// Owner
			$temp = array_unique(array_filter(explode(',,', $x['owner'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities['owner-availability-'.$value])) $rental->addAmenity($allAmenities['owner-availability-'.$value]);
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
					if (isset($allAmenities['congress-'.$value])) $rental->addAmenity($allAmenities['congress-'.$value]);
				}
			}

			// Amenities Wellness
			$temp = array_unique(array_filter(explode(',,', $x['amenities_wellness'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities['wellness-'.$value])) $rental->addAmenity($allAmenities['wellness-'.$value]);
				}
			}

			// Amenities Separate Groups
			if ($x['separate_groups'] == 1) $rental->addAmenity($context->rentalAmenityRepositoryAccessor->get()->findOneBySlug('separate-groups'));

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

					$answerEntity = $context->rentalInterviewAnswerRepositoryAccessor->get()->createNew(FALSE);
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

			$rental->checkIn = (int)$x['check_in'];

			$rental->checkOut = (int)$x['check_out'];

			$t = NULL;
			if ($x['price_offseason']>0) {
				if ($x['price_season_currency'] != $rental->primaryLocation->defaultCurrency->oldId) {
					$oldCurrency = $context->currencyRepositoryAccessor->get()->findOneByOldId($x['price_season_currency']);
					if ($oldCurrency) {
						$t = new \Extras\Types\Price($x['price_offseason'], $oldCurrency);
					}
				} else {
					$t = new \Extras\Types\Price($x['price_offseason'], $rental->primaryLocation->defaultCurrency);
				}
				if ($t) {
					//$rental->price = $t->convertToFloat($rental->primaryLocation->defaultCurrency);
					$rental->price = $t;
				}
			}

			if (strlen($x['capacity_max'])) {
				$rental->maxCapacity = $x['capacity_max'];
			}

			// Pricelist Rows
			$temp = unserialize(stripslashes($x['prices_simple']));
			if (is_array($temp) && count($temp)) {
				$sort = 0;
				$temp = array_chunk($temp, 6);
				foreach ($temp as $key => $value) {
					$row = $context->rentalPricelistRowRepositoryAccessor->get()->createNew(FALSE);
					$row->sort = $sort; $sort++;
					$row->roomCount = $value[0];
					if(isset($roomTypes[$value[1]])) $row->roomType = $roomTypes[$value[1]];
					$row->bedCount = $value[2];
					$row->extraBedCount = $value[3];

					if ($value[5] != $rental->primaryLocation->defaultCurrency->oldId) {
						$oldCurrency = $context->currencyRepositoryAccessor->get()->findOneByOldId($value[5]);
						if ($oldCurrency) {
							$t = new \Extras\Types\Price($value[4], $oldCurrency);						
						}
					} else {
						$t = new \Extras\Types\Price($value[4], $rental->primaryLocation->defaultCurrency);
					}

					$rental->addPricelistRow($row);
					$row->price = $t;
				}
			}
			$model->persist($rental);

			// Pricelists
			$temp = unserialize(stripslashes($x['prices_upload']));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (!is_file('http://www.tralandia.sk/u/'.$value[4])) continue;
					$pricelist = $context->rentalPricelistRepositoryAccessor->get()->createNew(FALSE);
					$pricelistDecorator = $context->rentalPricelistDecoratorFactory->create($pricelist);
					$pricelistDecorator->setContentFromFile('http://www.tralandia.sk/u/'.$value[4]);
					$pricelistDecorator->getEntity()->name = $value[2];
					$pricelistDecorator->getEntity()->rental = $rental;
					$pricelistDecorator->getEntity()->language = $context->languageRepositoryAccessor->get()->findOneByOldId($value[1]);
					//d($pricelistDecorator);
				}
			}

			// // Images
			// $temp = array_unique(array_filter(explode(',', $x['photos'])));
			// if (is_array($temp) && count($temp)) {
			// 	if ($this->developmentMode == TRUE) $temp = array_slice($temp, 0, 6);
			// 	foreach ($temp as $key => $value) {
			// 		$image = $context->rentalImageRepositoryAccessor->get()->findOneByOldUrl('http://www.tralandia.com/u/'.$value);
			// 		if (!$image) {
			// 			$imageEntity = $context->rentalImageRepositoryAccessor->get()->createNew(FALSE);
			// 			$image = $context->rentalImageDecoratorFactory->create($imageEntity);
			// 			$image->setContentFromFile('http://www.tralandia.com/u/'.$value);
			// 			$rental->addImage($imageEntity);
			// 		} else {
			// 			$rental->addImage($image);
			// 		}
			// 	}
			// }

			// Classification
			if ($x['classification'] > 0 && $x['classification'] != 9 ) {
				$rental->classification = (float)$x['classification'];
			} else if ($x['classification'] == 9) {
				$rental->classification = (float)0;
			}
		
			// Calendar
			$temp = array_unique(array_filter(explode(',', $x['calendar'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					//if ($value < $now) continue;
					$t = new \Nette\DateTime;
					$t->from($value);
					$temp[$key] = $t;
					d('tu som');
				}
			}

			$rental->calendar = $temp;

			$rental->calendarUpdated = fromStamp($x['calendar_updated']);

			$rental->created = fromStamp($x['date_added']);

			$rentalDecorator = $context->rentalDecoratorFactory->create($rental);
			$rentalDecorator->calculateRank();
			//d($rental->phones);
			$model->persist($rental);
			$this->savedVariables['lastRentalImported'] = $x['id'];
		}
		$model->flush();
		$this->saveVariables();
	}

}