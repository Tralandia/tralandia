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
			'phones' => array('compulsory' => TRUE),
			'emails' => array('compulsory' => TRUE),
			'urls' => array('compulsory' => FALSE),
			'amenities' => array('compulsory' => TRUE),
			'tags' => array('compulsory' => TRUE),
			'spokenLanguages' => array('compulsory' => TRUE),
			'checkIn' => array('compulsory' => FALSE),
			'checkOut' => array('compulsory' => FALSE),
			'maxCapacity' => array('compulsory' => TRUE),
			'price' => array('compulsory' => FALSE),
			'priceLists' => array('compulsory' => FALSE),
			'classification' => array('compulsory' => FALSE),
			'images' => array('compulsory' => TRUE),
		);

		foreach ($informationTypes as $key => $value) {
			$t = $context->rentalInformationRepositoryAccessor->get()->createNew(FALSE);

			$t->name = $this->createNewPhrase($nameDictionaryType);
			$t->slug = $key;
			$t->compulsory = $value['compulsory'];
			$model->persist($t);
		}

		// Import Room types
		$nameDictionaryType = $this->createPhraseType('\Rental\RoomType', 'name', 'NATIVE', array());
		$model->flush();

		$roomTypes = array(
			'room' => array('en' => 'room'),
			'apartment' => array('en' => 'apartment'),
			'building' => array('en' => 'building'),
		);

		foreach ($roomTypes as $key => $value) {
			$t = $context->rentalRoomTypeRepositoryAccessor->get()->createNew(FALSE);

			$t->name = $this->createNewPhrase($nameDictionaryType);
			$t->slug = $key;
			$model->persist($t);
		}

		$model->flush();
	}

}