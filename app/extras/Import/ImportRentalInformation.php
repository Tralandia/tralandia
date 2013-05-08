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

class ImportRentalInformation extends BaseImport {

	public function doImport($subsection = NULL) {
		//\Extras\Models\Service::flush(TRUE);

		$context = $this->context;
		$model = $this->model;

		$en = $context->languageRepositoryAccessor->get()->findOneByIso('en');
		$sk = $context->languageRepositoryAccessor->get()->findOneByIso('sk');


		$nameDictionaryType = $this->createPhraseType('\Rental\Information', 'name', 'NATIVE', array());
		$model->flush();

		$informationTypes = array(
			'primaryLocation' => array('compulsory' => TRUE),
			'type' => array('compulsory' => TRUE),
			'name' => array('compulsory' => TRUE),
			'teaser' => array('compulsory' => FALSE),
			'interviewAnswers' => array('compulsory' => FALSE),
			'addressGps' => array('compulsory' => TRUE),
			'addressLocality' => array('compulsory' => TRUE),
			'addressAddress' => array('compulsory' => TRUE),
			'addressPostalCode' => array('compulsory' => FALSE),
			'contactName' => array('compulsory' => TRUE),
			'phone' => array('compulsory' => TRUE),
			'email' => array('compulsory' => TRUE),
			'url' => array('compulsory' => FALSE),
			'amenities' => array('compulsory' => TRUE),
			'spokenLanguages' => array('compulsory' => TRUE),
			'checkIn' => array('compulsory' => FALSE),
			'checkOut' => array('compulsory' => FALSE),
			'maxCapacity' => array('compulsory' => TRUE),
			'price' => array('compulsory' => FALSE),
			'pricelists' => array('compulsory' => FALSE),
			'pricelistRows' => array('compulsory' => FALSE),
			'classification' => array('compulsory' => FALSE),
			'rooms' => array('compulsory' => FALSE),
			'bedroomCount' => array('compulsory' => FALSE),
			'images' => array('compulsory' => FALSE),
		);

		foreach ($informationTypes as $key => $value) {
			$t = $context->rentalInformationRepositoryAccessor->get()->createNew(FALSE);

			$t->name = $this->createNewPhrase($nameDictionaryType);
			$t->slug = $key;
			$t->compulsory = $value['compulsory'];
			$model->persist($t);
		}

		// Import Room types
		$nameDictionaryType = $this->createPhraseType('\Rental\RoomType', 'name', 'NATIVE', array('pluralVariationsRequired' => TRUE));
		$model->flush();

		$roomTypes = array(
			'room' => 100118,
			'apartment' => 100119,
			'building' => 100120,
		);

		foreach ($roomTypes as $key => $value) {
			$t = $context->rentalRoomTypeRepositoryAccessor->get()->createNew(FALSE);

			$t->name = $context->phraseRepositoryAccessor->get()->findOneByOldId($value);
			$t->slug = $key;
			$model->persist($t);
		}

		$model->flush();
	}

}