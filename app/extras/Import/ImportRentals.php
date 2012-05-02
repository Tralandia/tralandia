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
	Service\Log\Change as SLog;

class ImportRentals extends BaseImport {

	public function doImport($subsection = NULL) {

		$import = new \Extras\Import\BaseImport();
		$import->undoSection('rentals');

		$r = q('select * from objects_types_new where trax_en_type_id > 0');
		while($x = mysql_fetch_array($r)) {
			$oldRentalTypesEn[$x['id']] = $x['trax_en_type_id'];
		}

		$locationTypes = array();
		$locationTypes['country'] = \Service\Location\Type::getBySlug('country');
		$locationTypes['continent'] = \Service\Location\Type::getBySlug('continent');
		$locationTypes['region'] = \Service\Location\Type::getBySlug('region');
		$locationTypes['administrativeregionlevelone'] = \Service\Location\Type::getBySlug('administrativeregionlevelone');
		$locationTypes['administrativeregionleveltwo'] = \Service\Location\Type::getBySlug('administrativeregionleveltwo');
		$locationTypes['locality'] = \Service\Location\Type::getBySlug('locality');

		$nameDictionaryType = $this->createDictionaryType('\Rental\Rental', 'name', 'incomingLanguages', 'NATIVE', array('checkingRequired' => TRUE));
		$briefDescriptionDictionaryType = $this->createDictionaryType('\Rental\Rental', 'briefDescription', 'incomingLanguages', 'NATIVE', array('checkingRequired' => TRUE));
		$descriptionDictionaryType = $this->createDictionaryType('\Rental\Rental', 'description', 'incomingLanguages', 'NATIVE', array('checkingRequired' => TRUE));
		$teaserDictionaryType = $this->createDictionaryType('\Rental\Rental', 'teaser', 'incomingLanguages', 'NATIVE', array('checkingRequired' => TRUE));

		$ownerRole = \Service\User\Role::getBySlug('owner');

		$en = \Service\Dictionary\Language::getByIso('en');
		$now = time();

		if ($this->developmentMode == TRUE) {
			$r = q('select * from objects where country_id = 46 limit 20');
		} else {
			$r = q('select * from objects');
		}

		while ($x = mysql_fetch_array($r)) {
			debug($x['id'], $x['member_id']);
			$rental = \Service\Rental\Rental::get();
			$rental->oldId = $x['id'];

			$user = \Service\User\User::getByLogin(qc('select email from members where id = '.$x['member_id']));
			if (!$user) {
				$user = qNew('select id from user_user where isOwner = 1 and oldId = '.$x['member_id']);
				$user = mysql_fetch_array($user);
				if ($user['id'] > 0) {
					$user = \Service\User\User::get($user['id']);
					if (!$user) continue; //@todo - toto je dalsia chyba, treba to dat do logov
				} else {
					//@todo - treba zapisat do logov, ze sa nenasiel user, ktory sa hladal, docasne sa to ignoruje...
					continue;
				}
			}
			$rental->user = $user;

			$rental->editLanguage = \Service\Dictionary\Language::getByOldId($x['edit_language_id']);

			$rental->status = $x['live'] == 1 ? \Entity\Rental\Rental::STATUS_LIVE : \Entity\Rental\Rental::STATUS_DRAFT;

			$oldRentalTypes = array_unique(array_filter(explode(',', $x['objects_types_new'])));
			foreach ($oldRentalTypes as $key => $value) {
				$rental->addType(\Service\Rental\Type::getByOldId($oldRentalTypesEn[$value]));
			}

			$rental->timeDeleted = fromStamp($x['time_deleted']);

			// Locations
			$rental->addLocation(\Service\Location\Location::getByOldIdAndType($x['country_id'], $locationTypes['country']));

			$administrativeRegion = \Service\Location\Location::getByOldIdAndType($x['region_admin_id'], $locationTypes['administrativeregionlevelone']);
			if (!$administrativeRegion) {
				$administrativeRegion = \Service\Location\Location::getByOldIdAndType($x['region_admin_id'], $locationTypes['administrativeregionleveltwo']);
			}

			if ($administrativeRegion) {
				$rental->addLocation($administrativeRegion);
			}

			$regions = array_unique(array_filter(explode(',', $x['regions'])));
			if (is_array($regions) && count($regions)) {
				foreach ($regions as $key => $value) {
					$temp = \Service\Location\Location::getByOldIdAndType($value, $locationTypes['region']);
					if ($temp) $rental->addLocation($temp);
				}
			}

			$rental->addLocation(\Service\Location\Location::getByOldIdAndType($x['locality_id'], $locationTypes['locality']));

			$thisLocality = \Service\Location\Location::getByOldIdAndType($x['locality_id'], $locationTypes['locality']);

			$thisNamePhrase = \Service\Dictionary\Phrase::get($thisLocality->name);
			$thisCountry = \Service\Location\Location::get($thisLocality->parentId);
			$thisCountryNamePhrase = \Service\Dictionary\Phrase::get($thisCountry->name);
			$rental->address = new \Extras\Types\Address(array(
				'address' => array_filter(array($x['address'])),
				'postcode' => $x['post_code'],
				'locality' => $thisNamePhrase->getTranslation($thisCountry->defaultLanguage)->translation,
				'sublocality' => $x['sublocality'],
				'country' => $thisCountryNamePhrase->getTranslation($en)->translation,
			));

			$rental->latitude = new \Extras\Types\Latlong($x['latitude']);
			$rental->longitude = new \Extras\Types\Latlong($x['longitude']);

			$rental->slug = $x['name_url'];
			$temp = \Service\Dictionary\Language::get($rental->editLanguage);
			$rental->name = $this->createPhraseFromString('\Rental\Rental', 'name', 'incomingLanguages', 'NATIVE', $x['name'], $temp);
			
			//$rental->briefDescription = '';
			$rental->description = $this->createNewPhrase($descriptionDictionaryType, $x['description_dic_id']);
			if ($x['description_checked'] == 1) {
				$thisPhrase = \Service\Dictionary\Phrase::get($rental->description);
				$thisTranslation = $thisPhrase->getTranslation($rental->editLanguage);
				if ($thisTranslation) {
					$thisTranslation->checked = TRUE;
					$thisTranslation->save();
				}
			}
			$rental->teaser = $this->createNewPhrase($descriptionDictionaryType, $x['marketing_dic_id']);


			// Contacts
			if (strlen($x['contact_name'])) {
				$rental->addContact($this->createContact('name', $x['contact_name']));
			}

			$x['contact_phone'] = @unserialize($x['contact_phone']);
			if (is_array($x['contact_phone'])) {
				foreach ($x['contact_phone'] as $key => $value) {
					$value = implode('', $value);
					if (strlen($value)) {
						$rental->addContact($this->createContact('phone', $value));					
					}
				}
			}

			if(\Nette\Utils\Validators::isEmail($x['contact_email'])) {
				$rental->addContact($this->createContact('email', $x['contact_email']));
			}

			if (strlen($x['contact_skype'])) {
				$rental->addContact($this->createContact('skype', $x['contact_skype']));
			}

			if(\Nette\Utils\Validators::isUrl($x['contact_url'])) {
				$rental->addContact($this->createContact('url', $x['contact_url']));
			}

			if(\Nette\Utils\Validators::isUrl($x['url'])) {
				$rental->addContact($this->createContact('url', $x['url']));
			}

			$spokenLanguages = array_unique(array_filter(explode(',', $x['languages_spoken'])));
			if (is_array($spokenLanguages) && count($spokenLanguages)) {
				foreach ($spokenLanguages as $key => $value) {
					$rental->addSpokenLanguage(\Service\Dictionary\Language::getByOldId($value));
				}
			}

			// Amenities
			$allAmenities = array();
			$r1 = qNew('select * from rental_amenity_amenity');
			while ($x1 = mysql_fetch_array($r1)) {
				$allAmenities[$x1['oldId']] = \Service\Rental\Amenity\Amenity::get($x1['id']);
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
			$temp = unserialize($x['prices_simple']);
			if (is_array($temp) && count($temp)) {
				$pricelists['simple'] = $temp;
			}

			$temp = unserialize($x['prices_advanced']);
			if (is_array($temp) && count($temp)) {
				$pricelists['advanced'] = $temp;
			}

			$temp = unserialize($x['prices_upload']);
			if (is_array($temp) && count($temp)) {
				$pricelists['upload'] = $temp;
			}

			$rental->pricelists = $pricelists;

			// Media
			$temp = array_unique(array_filter(explode(',', $x['photos'])));
			if (is_array($temp) && count($temp)) {
				$temp = array_slice($temp, 0, 3);
				foreach ($temp as $key => $value) {
					$medium = \Service\Medium\Medium::createFromUrl('http://www.tralandia.com/u/'.$value);
					if ($medium) $rental->addMedium($medium);
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

			$rental->save();
			$rental->created = fromStamp($x['date_added']);
			$rental->save();
			\Extras\Models\Service::flush(FALSE);


			//@todo
			//$rental->rank = $rental->calculateRank(); // urobit tuto fciu
		}

		$this->savedVariables['importedSections']['rentals'] = 1;

	}

}