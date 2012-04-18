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

		$groups = array(
			array('activity', 'activities', 'activity'),
			array('general amenity', 'general amenities', 'general-amenity'),
			array('wellness option', 'wellness options', 'wellness-option'),
			array('congress option', 'congress options', 'congress-option'),
			array('tag', 'tags', 'tag'),
			array('room type', 'room types', 'room-type'),
			array('owner availability option', 'owner availability options', 'owner-availability-option'),
			array('board', 'board', 'board'),
			array('other', 'other', 'other'),
		);

		$en = \Service\Dictionary\Language::getByIso('en');

		foreach ($groups as $key => $value) {
			$g = \Service\Rental\Amenity\Group::get();
			$g->name = $this->createPhraseFromString('\Rental\Amenity\Group', 'name', 'supportedLanguages', 'ACTIVE', $value[0], $en);
			$g->namePlural = $this->createPhraseFromString('\Rental\Amenity\Group', 'name', 'supportedLanguages', 'ACTIVE', $value[1], $en);
			$g->slug = $value[2];
			debug($g); return;
			$g->save();
		}

		\Extras\Models\Service::flush(FALSE);

		// Activities
		$amenityGroup = \Service\Rental\Amenity\Group::getBySlug('activity');
		$nameDictionaryType = createDictionaryType('\Rental\Amenity\Amenity', 'name', 'supportedLanguages', 'ACTIVE', array('multitranslationRequired' => TRUE));
		$r = q('select * from activities');
		while ($x = mysql_fetch_array($r)) {
			$amenity = \Service\Rental\Amenity\Amenity::get();
			$amenity->group = $amenityGroup;
			$amenity->name = createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			debug($amenity); return;
			$amenity->save();
		}

		$this->savedVariables['importedSections']['amenities'] = 1;

	}

}