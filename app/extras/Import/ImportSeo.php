<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Dictionary as D,
	Service\Log as SLog;

class ImportSeo extends BaseImport {

	public function doImport($subsection) {	

		$this->{$subsection}();

	}

	private function importSeoUrls() {
		$context = $this->context;
		$model = $this->model;


		// Detaching all media
		// qNew('update medium set seoUrl_id = NULL where seoUrl_id > 0');

		$languagesByOldId = getNewIdsByOld('\Language');

		// Note: we don't import those 40 texts written for object_type pages, becuase we can't pair them properly...
		$r = q('SELECT seo_urls.* 
			FROM seo_urls LEFT JOIN seo_urls_texts ON seo_urls_texts.seo_url_id = seo_urls.id 
			WHERE length(seo_urls_texts.description) > 0 AND object_type_id = 0 AND attraction_id = 0
			GROUP BY seo_urls.id');

		$dictionaryTypeTitle = $this->createPhraseType('\Seo\SeoUrl', 'title', 'ACTIVE');
		$dictionaryTypeHeading = $this->createPhraseType('\Seo\SeoUrl', 'heading', 'ACTIVE');
		$dictionaryTypeTabName = $this->createPhraseType('\Seo\SeoUrl', 'tabName', 'ACTIVE');
		$dictionaryTypeDescription = $this->createPhraseType('\Seo\SeoUrl', 'description', 'ACTIVE');
		$dictionaryTypePpcKeywords = $this->createPhraseType('\Seo\SeoUrl', 'ppcKeywords', 'ACTIVE');

		$model->flush();

		$locationLocalityType = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('locality');
		$locationRegionType = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('region');
		$locationCountryType = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('country');

		$tagType = $context->rentalAmenityTypeRepositoryAccessor->get()->findOneBySlug('tag');
		debug(mysql_num_rows($r));
		while($x = mysql_fetch_array($r)) {
			$seoUrl = $context->seoSeoUrlEntityFactory->create();
			$seoUrl->oldId = $x['id'];

			// Location
			if ($x['locality_id'] > 0) {
				$location = $context->locationRepositoryAccessor->get()->findOneBy(array('type'=>$locationLocalityType, 'oldId'=>$x['locality_id']));
			} else if ($x['region_id'] > 0) {
				$location = $context->locationRepositoryAccessor->get()->findOneBy(array('type'=>$locationRegionType, 'oldId'=>$x['region_id']));
			} else {
				$location = $context->locationRepositoryAccessor->get()->findOneBy(array('type'=>$locationCountryType, 'oldId'=>$x['country_id']));
			}
			if ($location) {
				$seoUrl->location = $location;
			}

			// Tag
			if ($x['tag_id'] > 0) {
				$tag = $context->rentalAmenityRepositoryAccessor->get()->findOneBy(array('type'=>$tagType, 'oldId'=>$x['tag_id']));
				if ($tag) {
					$seoUrl->tag = $tag;
				}
			}

			$model->persist($seoUrl);
			// Media
			$temp = array_unique(array_filter(explode(',', $x['photos'])));
			if (is_array($temp) && count($temp)) {
				if ($this->developmentMode == TRUE) $temp = array_slice($temp, 0, 3);
				foreach ($temp as $key => $value) {
					$medium = $context->mediumRepositoryAccessor->get()->findOneByOldUrl('http://www.tralandia.com/u/'.$value);
					if (!$medium) {
						$medium = $context->mediumRepositoryAccessor->get()->createNew(FALSE);
						$mediumService = $context->mediumDecoratorFactory->create($medium);
						$mediumService->setContentFromUrl('http://www.tralandia.com/u/'.$value);
						$seoUrl->addMedium($medium);
					} else {
						$seoUrl->addMedium($medium);
					}
				}
			}

			$titlePhrase = $this->context->phraseRepositoryAccessor->get()->createNew(FALSE);
			$titlePhraseService = $this->context->phraseDecoratorFactory->create($titlePhrase);
			$titlePhrase->type = $dictionaryTypeTitle;
			$sourceLanguage = $context->languageRepositoryAccessor->get()->find($languagesByOldId[$x['source_language_id']]);
			if (isset($languagesByOldId[$x['source_language_id']])) {
				$titlePhrase->sourceLanguage = $sourceLanguage;
			}

			$headingPhrase = $this->context->phraseRepositoryAccessor->get()->createNew(FALSE);
			$headingPhraseService = $this->context->phraseDecoratorFactory->create($headingPhrase);
			$headingPhrase->type = $dictionaryTypeHeading;
			if (isset($languagesByOldId[$x['source_language_id']])) {
				$headingPhrase->sourceLanguage = $sourceLanguage;
			}

			$tabNamePhrase = $this->context->phraseRepositoryAccessor->get()->createNew(FALSE);
			$tabNamePhraseService = $this->context->phraseDecoratorFactory->create($tabNamePhrase);
			$tabNamePhrase->type = $dictionaryTypeTabName;
			if (isset($languagesByOldId[$x['source_language_id']])) {
				$tabNamePhrase->sourceLanguage = $sourceLanguage;
			}

			$descriptionPhrase = $this->context->phraseRepositoryAccessor->get()->createNew(FALSE);
			$descriptionPhraseService = $this->context->phraseDecoratorFactory->create($descriptionPhrase);
			$descriptionPhrase->type = $dictionaryTypeDescription;
			if (isset($languagesByOldId[$x['source_language_id']])) {
				$descriptionPhrase->sourceLanguage = $sourceLanguage;
			}

			$r1 = q('select * from seo_urls_texts where seo_url_id = '.$x['id'].' and length(description)>0');
			while ($x1 = mysql_fetch_array($r1)) {
				$languageTemp = $context->languageRepositoryAccessor->get()->find($languagesByOldId[$x1['language_id']]);
				
				// Title
				$titlePhraseService->createTranslation($languageTemp, $x1['title']);
				
				// Heading
				$headingPhraseService->createTranslation($languageTemp, $x1['h1']);
				
				// Tab Heading
				$tabNamePhraseService->createTranslation($languageTemp, $x1['tab_name']);
				
				// Description
				$descriptionPhraseService->createTranslation($languageTemp, $x1['description']);
			}

			$seoUrl->title = $titlePhrase;
			$seoUrl->heading = $headingPhrase;
			$seoUrl->tabName = $tabNamePhrase;
			$seoUrl->description = $descriptionPhrase;
			//debug($titlePhrase->getMainEntity()->translations);
			//debug($descriptionPhrase->getMainEntity()->translations); return;
		}
		$model->flush();
	}


}