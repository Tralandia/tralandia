<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportAmenities extends BaseImport {

	private $importantAmenities = array('air-conditioning', 'fitness', 'fitness-centrum', 'indoor-pool', 'indoor-swimming-pool', 'internet', 'jacuzzi', 'kids-corner', 'pizzeria', 'sauna', 'sauna-finnish', 'sauna-infrared', 'sauna-steam', 'solarium', 'swimming-pool', 'tennis-court', 'wireless-internet-wifi', 'small-pets', 'medium-pets', 'any-pets');

	public function doImport($subsection = NULL) {

		//$this->undoSection('amenities');

		$en = $this->context->languageRepositoryAccessor->get()->findOneByIso('en');
		$sk = $this->context->languageRepositoryAccessor->get()->findOneByIso('sk');

		$groups = array(
			array('important', 'important', 'important'),
			array('location', 'locations', 'location'),
			array('children', 'children', 'children'),
			array('activity', 'activities', 'activity'),
			array('relax', 'relax', 'relax'),
			array('board', 'board', 'board'),
			array('service', 'services', 'service'),
			array('wellness', 'wellness', 'wellness'),
			array('congress', 'congress', 'congress'),
			array('kitchen', 'kitchens', 'kitchen'),
			array('bathroom', 'bathrooms', 'bathroom'),
			array('room', 'room', 'room'),
			array('heating', 'heating', 'heating'),
			array('parking', 'parking', 'parking'),
			// array('tag', 'tags', 'tag'),
			array('room type', 'room types', 'room-type'),
			array('owner availability', 'owner availability', 'owner-availability'),
			array('separate group', 'separate groups', 'separate-groups'),
			array('animal', 'animals', 'animal'),
			array('other', 'other', 'other'),
		);

		$en = $this->context->languageRepositoryAccessor->get()->findOneByIso('en');

		$nameDictionaryType = $this->createPhraseType('\Rental\Amenity', 'name', 'ACTIVE', array('pluralVariationsRequired' => TRUE));
		$this->createPhraseType('\Rental\AmenityType', 'name', 'ACTIVE');
		$this->model->flush();


		foreach ($groups as $key => $value) {
			$g = $this->context->rentalAmenityTypeEntityFactory->create();
			$g->name = $this->createPhraseFromString('\Rental\AmenityType', 'name', 'ACTIVE', $value[0], $en);
			$g->slug = $value[2];
			$g->sorting = $key;
			$this->model->persist($g);
		}
		$this->model->flush();


		// Activities
		$amenityType = $this->context->rentalAmenityTypeRepositoryAccessor->get()->findOneBySlug('activity');
		$r = q('select * from activities');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$amenity->oldId = $x['id'];
			if (in_array($amenity->slug, $this->importantAmenities)) $amenity->important = TRUE;
			$this->model->persist($amenity);
		}
		$this->model->flush();

		// General Amenities
		$subGroups = explode(',', 'other,important,children,room,kitchen,bathroom,heating,parking,relax,service');
		foreach ($subGroups as $key => $value) {
			$amenityType = $this->context->rentalAmenityTypeRepositoryAccessor->get()->findOneBySlug($value);
			$r = q('select * from amenities_general where type_id = '.$key);
			while ($x = mysql_fetch_array($r)) {
				$amenity = $this->context->rentalAmenityEntityFactory->create();
				$amenity->type = $amenityType;
				$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
				$amenity->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
				$amenity->oldId = $x['id'];
				if (in_array($amenity->slug, $this->importantAmenities)) $amenity->important = TRUE;
				$this->model->persist($amenity);
			}
		}
		$this->model->flush();

		// Wellness Options
		$amenityType = $this->context->rentalAmenityTypeRepositoryAccessor->get()->findOneBySlug('wellness');
		$r = q('select * from amenities_wellness');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$amenity->oldId = $x['id'];
			if (in_array($amenity->slug, $this->importantAmenities)) $amenity->important = TRUE;
			$this->model->persist($amenity);
		}
		$this->model->flush();

		// Congress Options
		$amenityType = $this->context->rentalAmenityTypeRepositoryAccessor->get()->findOneBySlug('congress');
		$r = q('select * from amenities_congress');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$amenity->oldId = $x['id'];
			if (in_array($amenity->slug, $this->importantAmenities)) $amenity->important = TRUE;
			$this->model->persist($amenity);
		}
		$this->model->flush();

		// Room Types
		$amenityType = $this->context->rentalAmenityTypeRepositoryAccessor->get()->findOneBySlug('room-type');
		$r = q('select * from room_types');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$amenity->oldId = $x['id'];
			if (in_array($amenity->slug, $this->importantAmenities)) $amenity->important = TRUE;
			$this->model->persist($amenity);
		}
		$this->model->flush();

		// Owner availabilities
		$amenityType = $this->context->rentalAmenityTypeRepositoryAccessor->get()->findOneBySlug('owner-availability');
		$r = q('select * from owner');
		$ownerOptions = array(
			array('Owner is available at check-in and check-out.', 'Majiteľ je dostupný pri príchode a odchode.', 6),
			array('Owner is available by phone.', 'Majiteľ je dostupný na telefóne.', 5),
			array('Owner is available during your stay.', 'Majiteľ je dostupný počas pobytu.', 0),
			array('Owner lives nearby.', 'Majiteľ býva v blízkosti.', 4),
			array('Owner lives on premises.', 'Majiteľ býva v objekte.', 3),

			array('Property manager is available at check-in and check-out.', 'Manažér je dostupný pri príchode a odchode.', 0),
			array('Property manager is available by phone.', 'Manažér je dostupný na telefóne.', 0),
			array('Property manager is available during your stay.', 'Manažér je dostupný počas pobytu.', 0),

			array('Reception is available.', 'Recepcia.', 8),
			array('Reception is available 24/7.', 'Recepcia 24/7.', 9),
		);
		foreach ($ownerOptions as $key => $value) {
			$name = $this->context->phraseEntityFactory->create();
			$name->type = $nameDictionaryType;
			$name->createTranslation($en, $value[0]);
			$name->createTranslation($sk, $value[1]);

			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $name;
			$amenity->slug = $value[0];
			$amenity->oldId = $value[2];
			$amenity->sorting = $key;
			$this->model->persist($amenity);
			d($amenity, $name);
		}
		$this->model->flush();

		// Board
		$amenityType = $this->context->rentalAmenityTypeRepositoryAccessor->get()->findOneBySlug('board');
		$r = q('select * from food');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$amenity->oldId = $x['id'];
			if (in_array($amenity->slug, $this->importantAmenities)) $amenity->important = TRUE;
			$this->model->persist($amenity);
		}
		$this->model->flush();

		// Separate Group
		$amenityType = $this->context->rentalAmenityTypeRepositoryAccessor->get()->findOneBySlug('separate-groups');

		$separateOptions = array(
			array(100077, 'separate-groups-yes'),
			array(100100, 'separate-groups-no'),
		);

		foreach ($separateOptions as $key => $value) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $value[0]);
			$amenity->slug = $value[1];
			$this->model->persist($amenity);
		}
		$this->model->flush();

		// Animals
		$amenityType = $this->context->rentalAmenityTypeRepositoryAccessor->get()->findOneBySlug('animal');
		$animalOptions = array(
			array('Pets are not allowed.', 'Domáce zvierá nie sú povolené.', 'no-pets'),
			array('Small pets are allowed.', 'Malé domáce zvieratá sú povolené.', 'small-pets'),
			array('Small or medium size pets are allowed.', 'Malé a stredne veľké domáce zvieratá sú povolené.', 'medium-pets'),
			array('All pets are allowed.', 'Všetky domáce zvieratá sú povolené.', 'any-pets'),
		);

		foreach ($animalOptions as $key => $value) {
			$name = $this->context->phraseEntityFactory->create();
			$name->type = $nameDictionaryType;
			$name->createTranslation($en, $value[0]);
			$name->createTranslation($sk, $value[1]);

			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $name;
			$amenity->slug = $value[2];
			$amenity->important = TRUE;
			$amenity->sorting = $key;
			$this->model->persist($amenity);
			d($amenity, $name);
		}

		$this->model->flush();


		// Placements
		$placementOptions = array(
			array('in town', 'v meste', 'in-town'),
		);
		$placementDictionaryType = $this->createPhraseType('\Rental\Placement', 'name', 'ACTIVE', array());

		foreach ($placementOptions as $key => $value) {
			$name = $this->context->phraseEntityFactory->create();
			$name->type = $placementDictionaryType;
			$name->createTranslation($en, $value[0]);
			$name->createTranslation($sk, $value[1]);

			$placement = $this->context->rentalPlacementEntityFactory->create();
			$placement->type = $amenityType;
			$placement->name = $name;
			$placement->slug = $value[2];
			$this->model->persist($placement);
			d($placement, $name);
		}
		$this->model->flush();

	}

}