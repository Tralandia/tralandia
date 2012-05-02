<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log\Change as ChangeLog;

class ImportAmenities extends BaseImport {

	public function doImport($subsection = NULL) {

		$import = new \Extras\Import\BaseImport();
		$import->undoSection('amenities');


		$groups = array(
			array('activity', 'activities', 'activity'),
			array('important', 'important', 'important'),
			array('children', 'children', 'children'),
			array('room', 'room', 'room'),
			array('kitchen', 'kitchens', 'kitchen'),
			array('bathroom', 'bathrooms', 'bathroom'),
			array('heating', 'heating', 'heating'),
			array('parking', 'parking', 'parking'),
			array('relax', 'relax', 'relax'),
			array('service', 'services', 'service'),
			array('wellness', 'wellness', 'wellness'),
			array('congress', 'congress', 'congress'),
			array('tag', 'tags', 'tag'),
			array('room type', 'room types', 'room-type'),
			array('owner availability', 'owner availability', 'owner-availability'),
			array('board', 'board', 'board'),
			array('other', 'other', 'other'),
		);

		$en = \Service\Dictionary\Language::getByIso('en');

		$nameDictionaryType = $this->createDictionaryType('\Rental\Amenity\Amenity', 'name', 'supportedLanguages', 'ACTIVE', array('multitranslationRequired' => TRUE));
		$tagNameDictionaryType = $this->createDictionaryType('\Rental\Amenity\Amenity', 'name-tag', 'supportedLanguages', 'ACTIVE', array('genderNumberRequired' => TRUE, 'positionRequired' => TRUE, 'webalizedRequired' => TRUE));
		$this->createDictionaryType('\Rental\Amenity\Group', 'name', 'supportedLanguages', 'ACTIVE');
		\Extras\Models\Service::flush(FALSE);


		foreach ($groups as $key => $value) {
			$g = \Service\Rental\Amenity\Group::get();
			$g->name = $this->createPhraseFromString('\Rental\Amenity\Group', 'name', 'supportedLanguages', 'ACTIVE', $value[0], $en);
			$g->slug = $value[2];
			$g->save();
		}

		\Extras\Models\Service::flush(FALSE);


		// Activities
		$amenityGroup = \Service\Rental\Amenity\Group::getBySlug('activity');
		$r = q('select * from activities');
		while ($x = mysql_fetch_array($r)) {
			$amenity = \Service\Rental\Amenity\Amenity::get();
			$amenity->group = $amenityGroup;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$amenity->save();
		}

		// General Amenities
		$subGroups = explode(',', 'other,important,children,room,kitchen,bathroom,heating,parking,relax,service');
		foreach ($subGroups as $key => $value) {
			$amenityGroup = \Service\Rental\Amenity\Group::getBySlug($value);
			$r = q('select * from amenities_general where type_id = '.$key);
			while ($x = mysql_fetch_array($r)) {
				$amenity = \Service\Rental\Amenity\Amenity::get();
				$amenity->group = $amenityGroup;
				$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
				$amenity->oldId = $x['id'];
				$amenity->save();
			}
		}

		// Wellness Options
		$amenityGroup = \Service\Rental\Amenity\Group::getBySlug('wellness');
		$r = q('select * from amenities_wellness');
		while ($x = mysql_fetch_array($r)) {
			$amenity = \Service\Rental\Amenity\Amenity::get();
			$amenity->group = $amenityGroup;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$amenity->save();
		}

		// Congress Options
		$amenityGroup = \Service\Rental\Amenity\Group::getBySlug('congress');
		$r = q('select * from amenities_congress');
		while ($x = mysql_fetch_array($r)) {
			$amenity = \Service\Rental\Amenity\Amenity::get();
			$amenity->group = $amenityGroup;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$amenity->save();
		}

		// Tags
		$amenityGroup = \Service\Rental\Amenity\Group::getBySlug('tag');
		$r = q('select * from tags where id = 15');
		while ($x = mysql_fetch_array($r)) {
			$amenity = \Service\Rental\Amenity\Amenity::get();
			$amenity->group = $amenityGroup;
			$amenity->name = $this->createNewPhrase($tagNameDictionaryType, $x['name_dic_id']);

			$r1 = q('select * from tags_positions where tag_id = '.$x['id']);
			$namePhraseService = \Service\Dictionary\Phrase::get($amenity->name);
			while ($x1 = mysql_fetch_array($r1)) {
				$thisTranslation = $namePhraseService->getTranslation(\Service\Dictionary\Language::getByOldId($x1['language_id']));
				if (!($thisTranslation instanceof \Service\Dictionary\Translation)) {
					$thisTranslation = \Service\Dictionary\Translation::get();
					$thisTranslation->language = \Service\Dictionary\Language::getByOldId($x1['language_id']);
					$namePhraseService->addTranslation($thisTranslation);
				}
				$variations = $thisTranslation->variations;
				$variations['position'] = $x1['position'];
				$genderNumberOptions = explode2Levels("\n", ':', $x1['variations']);
				$t = array();
				foreach ($genderNumberOptions as $key => $value) {
					if (strlen(trim($value))) {
						$t[trim($key)] = trim($value);
					}
				}
				$variations['genderNumberOptions'] = $t;
				$thisTranslation->variations = $variations;

				$thisTranslation->save();
			}
			$details = array(
				'objectOriented' => $x['object_oriented'],
			);
			$amenity->details = $details;
			$amenity->oldId = $x['id'];
			$amenity->save();
		}


		// Room Types
		$amenityGroup = \Service\Rental\Amenity\Group::getBySlug('room-type');
		$r = q('select * from room_types');
		while ($x = mysql_fetch_array($r)) {
			$amenity = \Service\Rental\Amenity\Amenity::get();
			$amenity->group = $amenityGroup;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$amenity->save();
		}

		// Owner availabilities
		$amenityGroup = \Service\Rental\Amenity\Group::getBySlug('owner-availability');
		$r = q('select * from owner');
		while ($x = mysql_fetch_array($r)) {
			$amenity = \Service\Rental\Amenity\Amenity::get();
			$amenity->group = $amenityGroup;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$amenity->save();
		}

		// Board
		$amenityGroup = \Service\Rental\Amenity\Group::getBySlug('board');
		$r = q('select * from food');
		while ($x = mysql_fetch_array($r)) {
			$amenity = \Service\Rental\Amenity\Amenity::get();
			$amenity->group = $amenityGroup;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$amenity->save();
		}


		$this->savedVariables['importedSections']['amenities'] = 1;

	}

}