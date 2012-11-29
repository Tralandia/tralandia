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

class ImportAttractions extends BaseImport {

	public function doImport($subsection = NULL) {
		$context = $this->context;
		$model = $this->model;

		$en = $context->languageRepository->findOneByIso('en');

		// Detaching all media
		// qNew('update medium set attraction_id = NULL where attraction_id > 0');
		//return;


		$temp = array(
			'pluralsRequired' => TRUE,
		);

		$typeNameType = $this->createPhraseType('\Attraction\Type', 'name', 'ACTIVE', $temp);

		$r = q('select * from attractions_types order by id');
		while($x = mysql_fetch_array($r)) {
			$attractionType = $context->attractionTypeEntityFactory->create();
			$attractionType->name = $this->createNewPhrase($typeNameType, $x['name_singular_dic_id']);
			$attractionType->oldId = $x['id'];
			$model->persist($attractionType);
		}

		$model->flush();

		$attractionNameType = $this->createPhraseType('\Attraction\Attraction', 'name', 'ACTIVE');
		$attractionDescriptionType = $this->createPhraseType('\Attraction\Attraction', 'descrition', 'ACTIVE');

		$this->countryTypeId = qNew('select id from location_type where slug = "country"');
		$this->countryTypeId = mysql_fetch_array($this->countryTypeId);
		$locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$this->countryTypeId[0]);
		//$locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$countryTypeId);
		$languagesByOldId = getNewIdsByOld('\Language');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from attractions limit 4');
		} else {
			$r = q('select * from attractions order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$attraction = $context->attractionEntityFactory->create();
			$attraction->type = $context->attractionTypeRepository->findOneByOldId($x['attraction_type_id']);
			$attraction->name = $this->createNewPhrase($attractionNameType, $x['name_dic_id']);
			$attraction->description = $this->createNewPhrase($attractionDescriptionType, $x['description_dic_id']);
			$attraction->country = $context->locationRepository->find($locationsByOldId[$x['country_id']]);
			$attraction->latitude = new \Extras\Types\Latlong($x['latitude']);
			$attraction->longitude = new \Extras\Types\Latlong($x['longitude']);
			$attraction->oldId = $x['id'];

			$contacts = new \Extras\Types\Contacts();

			if(\Nette\Utils\Validators::isEmail($x['email'])) {
				$contacts->add(new \Extras\Types\Email($x['email']));
			}

			if (strlen($x['phone'])) {
				$contacts->add(new \Extras\Types\Phone($x['phone']));
			}

			if(\Nette\Utils\Validators::isUrl($x['url'])) {
				$contacts->add(new \Extras\Types\Url($x['url']));
			}

			$attraction->contacts = $contacts;

			$model->persist($attraction);
			// Media
			$temp = array_unique(array_filter(explode(',', $x['photos'])));
			if (is_array($temp) && count($temp)) {
				if ($this->developmentMode == TRUE) $temp = array_slice($temp, 0, 3);
				foreach ($temp as $key => $value) {
					$medium = $context->mediumRepository->findOneByOldUrl('http://www.tralandia.com/u/'.$value);
					if (!$medium) {
						$medium = $context->mediumRepositoryAccessor->get()->createNew();
						$mediumService = $context->mediumDecoratorFactory->create($medium);
						$mediumService->setContentFromUrl('http://www.tralandia.com/u/'.$value);
						$attraction->addMedium($medium);
					} else {
						$attraction->addMedium($medium);
					}
				}
			}
		}

		$model->flush();
	}
}