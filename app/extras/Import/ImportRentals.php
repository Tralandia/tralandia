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
		$model->persist($nameDictionaryType);
		$briefDescriptionDictionaryType = $this->createPhraseType('\Rental\Rental', 'briefDescription', 'NATIVE', array('checkingRequired' => TRUE));
		$model->persist($briefDescriptionDictionaryType);
		$descriptionDictionaryType = $this->createPhraseType('\Rental\Rental', 'description', 'NATIVE', array('checkingRequired' => TRUE));
		$model->persist($descriptionDictionaryType);
		$teaserDictionaryType = $this->createPhraseType('\Rental\Rental', 'teaser', 'NATIVE', array('checkingRequired' => TRUE));
		$model->persist($teaserDictionaryType);

		$model->flush();


		$r = q('select * from objects_types_new where trax_en_type_id > 0');
		while($x = mysql_fetch_array($r)) {
			$oldRentalTypesEn[$x['id']] = $x['trax_en_type_id'];
		}

		$locationTypes = array();
		$locationTypes['country'] = $context->locationTypeRepository->findOneBySlug('country');
		$locationTypes['continent'] = $context->locationTypeRepository->findOneBySlug('continent');
		$locationTypes['region'] = $context->locationTypeRepository->findOneBySlug('region');
		$locationTypes['administrativeregionlevelone'] = $context->locationTypeRepository->findOneBySlug('administrativeregionlevelone');
		$locationTypes['administrativeregionleveltwo'] = $context->locationTypeRepository->findOneBySlug('administrativeregionleveltwo');
		$locationTypes['locality'] = $context->locationTypeRepository->findOneBySlug('locality');

		$ownerRole = $context->userRoleRepository->findOneBySlug('owner');

		$en = $context->languageRepository->findOneByIso('en');
		$now = time();

		if ($this->developmentMode == TRUE) {
			$r = q('select * from objects where country_id = 46 order by rand() limit 1');
		} else {
			$r = q('select * from objects');
		}

		while ($x = mysql_fetch_array($r)) {
			if($context->rentalRepository->findOneByOldId($x['id'])) {
				continue;
			}
			$rental = $context->rentalEntityFactory->create();
			$rental->oldId = $x['id'];

			$user = $context->userRepository->findOneByLogin(qc('select email from members where id = '.$x['member_id']));
			if (!$user) {
				continue; // @todo dorobit
				$user = qNew('select id from user where isOwner = 1 and oldId = '.$x['member_id']);
				$user = mysql_fetch_array($user);
				if ($user['id'] > 0) {
					$user = $context->userRepository->find($user['id']);
				} else {
					//@todo - treba zapisat do logov, ze sa nenasiel user, ktory sa hladal, docasne sa to ignoruje...
					continue;
				}
			}
			$rental->user = $user;

			$rental->editLanguage = $context->languageRepository->findOneByOldId($x['edit_language_id']);

			$rental->status = $x['live'] == 1 ? $rental::STATUS_LIVE : $rental::STATUS_DRAFT;

			$oldRentalTypes = array_unique(array_filter(explode(',', $x['objects_types_new'])));
			foreach ($oldRentalTypes as $key => $value) {
				$rental->addType($context->rentalTypeRepository->findOneByOldId($oldRentalTypesEn[$value]));
			}

			// Locations
			$rental->addLocation($context->locationRepository->findOneBy(array('oldId' => $x['country_id'], 'type' => $locationTypes['country'])));

			$administrativeRegion = $context->locationRepository->findOneBy(array('oldId' => $x['region_admin_id'], 'type' => $locationTypes['administrativeregionlevelone']));
			if (!$administrativeRegion) {
				$administrativeRegion = $context->locationRepository->findOneBy(array('oldId' => $x['region_admin_id'], 'type' => $locationTypes['administrativeregionleveltwo']));
			}

			if ($administrativeRegion) {
				$rental->addLocation($administrativeRegion);
			}

			$regions = array_unique(array_filter(explode(',', $x['regions'])));
			if (is_array($regions) && count($regions)) {
				foreach ($regions as $key => $value) {
					$temp = $context->locationRepository->findOneBy(array('oldId' => $value, 'type' => $locationTypes['region']));
					if ($temp) $rental->addLocation($temp);
				}
			}

			// @todo dorobit
			// d(array('oldId' => $x['locality_id'], 'type' => $locationTypes['locality']));
			// $locationTemp = $context->locationRepository->findOneBy(array('oldId' => $x['locality_id'], 'type' => $locationTypes['locality']));
			// if($locationTemp) $rental->addLocation($locationTemp);

			// $thisLocality = $context->locationRepository->findOneBy(array('oldId' => $x['locality_id'], 'type' => $locationTypes['locality']));

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

			$contacts->add(new \Extras\Types\Url($x['contact_url']));
			$contacts->add(new \Extras\Types\Url($x['url']));

			$rental->contacts = $contacts;

			$spokenLanguages = array_unique(array_filter(explode(',', $x['languages_spoken'])));
			if (is_array($spokenLanguages) && count($spokenLanguages)) {
				foreach ($spokenLanguages as $key => $value) {
					$rental->addSpokenLanguage($context->languageRepository->findOneByOldId($value));
				}
			}

			// Amenities
			$allAmenities = array();
			$r1 = qNew('select * from rental_amenity');
			while ($x1 = mysql_fetch_array($r1)) {
				$allAmenities[$x1['oldId']] = $context->rentalAmenityRepository->find($x1['id']);
			}

			// Activities
			$temp = array_unique(array_filter(explode(',', $x['activities'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Food
			$temp = array_unique(array_filter(explode(',', $x['food'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Owner
			$temp = array_unique(array_filter(explode(',', $x['owner'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Amentities General
			$temp = array_unique(array_filter(explode(',', $x['amenities_general'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Amenities Congress
			$temp = array_unique(array_filter(explode(',', $x['amenities_congress'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Amenities Wellness
			$temp = array_unique(array_filter(explode(',', $x['amenities_wellness'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			// Tags
			$temp = array_unique(array_filter(explode(',', $x['tags'])));
			if (is_array($temp) && count($temp)) {
				foreach ($temp as $key => $value) {
					if (isset($allAmenities[$value])) $rental->addAmenity($allAmenities[$value]);
				}
			}

			if (strlen($x['check_in'])) {
				$rental->checkIn = $x['check_in'];
			}

			if (strlen($x['check_out'])) {
				$rental->checkOut = $x['check_out'];
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
					$medium = $context->mediumRepository->findOneByOldUrl('http://www.tralandia.com/u/'.$value);
					if (!$medium) {
						$medium = $context->mediumServiceFactory->create();
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