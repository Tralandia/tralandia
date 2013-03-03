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

	private $importantAmenities = array('air-conditioning', 'fitness', 'fitness-centrum', 'indoor-pool', 'indoor-swimming-pool', 'internet', 'jacuzzi', 'kids-corner', 'pizzeria', 'sauna', 'sauna-finnish', 'sauna-infrared', 'sauna-steam', 'solarium', 'swimming-pool', 'tennis-court', 'wireless-internet-wifi');

	public function doImport($subsection = NULL) {

		//$this->undoSection('amenities');


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

		$nameDictionaryType = $this->createPhraseType('\Rental\Amenity', 'name', 'ACTIVE', array('pluralsRequired' => TRUE));
		$this->createPhraseType('\Rental\AmenityType', 'name', 'ACTIVE');
		$this->model->flush();


		foreach ($groups as $key => $value) {
			$g = $this->context->rentalAmenityTypeEntityFactory->create();
			$g->name = $this->createPhraseFromString('\Rental\AmenityType', 'name', 'ACTIVE', $value[0], $en);
			$g->slug = $value[2];
			$g->sort = $key;
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
		$amenity = $this->context->rentalAmenityEntityFactory->create();
		$amenity->type = $amenityType;
		$amenity->name = $this->createNewPhrase($nameDictionaryType, 1011);
		$amenity->slug = 'separate-groups';
		if (in_array($amenity->slug, $this->importantAmenities)) $amenity->important = TRUE;
		$this->model->persist($amenity);
		$this->model->flush();

		// Animals
		$amenityType = $this->context->rentalAmenityTypeRepositoryAccessor->get()->findOneBySlug('animal');
		$amenity = $this->context->rentalAmenityEntityFactory->create();
		$amenity->type = $amenityType;
		$amenity->name = $this->createPhraseFromString('\Rental\Amenity', 'name', 'ACTIVE', 'small dog allowed', $en);
		$amenity->slug = 'small-dog';
		if (in_array($amenity->slug, $this->importantAmenities)) $amenity->important = TRUE;
		$this->model->persist($amenity);
		$this->model->flush();

	}

}