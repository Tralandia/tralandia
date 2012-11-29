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

	public function doImport($subsection = NULL) {

		//$this->undoSection('amenities');


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

		$en = $this->context->languageRepository->findOneByIso('en');

		$nameDictionaryType = $this->createPhraseType('\Rental\Amenity', 'name', 'ACTIVE', array('pluralsRequired' => TRUE));
		$tagNameDictionaryType = $this->createPhraseType('\Rental\Amenity', 'name-tag', 'ACTIVE', array('genderVariationsRequired' => TRUE, 'positionRequired' => TRUE));
		$this->createPhraseType('\Rental\AmenityType', 'name', 'ACTIVE');
		$this->model->flush();


		foreach ($groups as $key => $value) {
			$g = $this->context->rentalAmenityTypeEntityFactory->create();
			$g->name = $this->createPhraseFromString('\Rental\AmenityType', 'name', 'ACTIVE', $value[0], $en);
			$g->slug = $value[2];
			$this->model->persist($g);
		}
		$this->model->flush();


		// Activities
		$amenityType = $this->context->rentalAmenityTypeRepository->findOneBySlug('activity');
		$r = q('select * from activities');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$this->model->persist($amenity);
		}
		$this->model->flush();

		// General Amenities
		$subGroups = explode(',', 'other,important,children,room,kitchen,bathroom,heating,parking,relax,service');
		foreach ($subGroups as $key => $value) {
			$amenityType = $this->context->rentalAmenityTypeRepository->findOneBySlug($value);
			$r = q('select * from amenities_general where type_id = '.$key);
			while ($x = mysql_fetch_array($r)) {
				$amenity = $this->context->rentalAmenityEntityFactory->create();
				$amenity->type = $amenityType;
				$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
				$amenity->oldId = $x['id'];
				$this->model->persist($amenity);
			}
		}
		$this->model->flush();

		// Wellness Options
		$amenityType = $this->context->rentalAmenityTypeRepository->findOneBySlug('wellness');
		$r = q('select * from amenities_wellness');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$this->model->persist($amenity);
		}
		$this->model->flush();

		// Congress Options
		$amenityType = $this->context->rentalAmenityTypeRepository->findOneBySlug('congress');
		$r = q('select * from amenities_congress');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$this->model->persist($amenity);
		}
		$this->model->flush();

		// Tags
		$r = q('select * from tags');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalTagRepositoryAccessor->get()->createNew();
			$amenity->name = $this->createNewPhrase($tagNameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$this->model->persist($amenity);
		}
		$this->model->flush();


		// Room Types
		$amenityType = $this->context->rentalAmenityTypeRepository->findOneBySlug('room-type');
		$r = q('select * from room_types');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$this->model->persist($amenity);
		}
		$this->model->flush();

		// Owner availabilities
		$amenityType = $this->context->rentalAmenityTypeRepository->findOneBySlug('owner-availability');
		$r = q('select * from owner');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$this->model->persist($amenity);
		}
		$this->model->flush();

		// Board
		$amenityType = $this->context->rentalAmenityTypeRepository->findOneBySlug('board');
		$r = q('select * from food');
		while ($x = mysql_fetch_array($r)) {
			$amenity = $this->context->rentalAmenityEntityFactory->create();
			$amenity->type = $amenityType;
			$amenity->name = $this->createNewPhrase($nameDictionaryType, $x['name_dic_id']);
			$amenity->oldId = $x['id'];
			$this->model->persist($amenity);
		}
		$this->model->flush();

	}

}