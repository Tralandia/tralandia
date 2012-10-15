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
		$this->setSubsections('seo');

		$this->$subsection();

		$this->savedVariables['importedSubSections']['seo'][$subsection] = 1;

		if (end($this->sections['seo']['subsections']) == $subsection) {
			$this->savedVariables['importedSections']['seo'] = 1;		
		}
	}

	private function importSeoUrls() {
		$import = new \Extras\Import\BaseImport();
		// Detaching all media
		qNew('update medium_medium set seoUrl_id = NULL where seoUrl_id > 0');
		$import->undoSection('seo');

		$this->countryTypeId = qNew('select id from location_type where slug = "country"');
		$this->countryTypeId = mysql_fetch_array($this->countryTypeId);
		$countriesByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$this->countryTypeId[0]);

		$languagesByOldId = getNewIdsByOld('\Dictionary\Language');

		// Note: we don't import those 40 texts written for object_type pages, becuase we can't pair them properly...
		// Note: attraction_id - tiez neimportujeme, musi sa to spravit zvlast tym, ze sa tie descriptions naimportuju uz do entity atraction...
		$r = q('SELECT seo_urls.* 
			FROM seo_urls LEFT JOIN seo_urls_texts ON seo_urls_texts.seo_url_id = seo_urls.id 
			WHERE length(seo_urls_texts.description) > 0 AND object_type_id = 0 AND attraction_id = 0
			GROUP BY seo_urls.id');

		$dictionaryTypeTitle = $this->createPhraseType('\Seo\SeoUrl', 'title', 'ACTIVE');
		$dictionaryTypeHeading = $this->createPhraseType('\Seo\SeoUrl', 'heading', 'ACTIVE');
		$dictionaryTypeTabName = $this->createPhraseType('\Seo\SeoUrl', 'tabName', 'ACTIVE');
		$dictionaryTypeDescription = $this->createPhraseType('\Seo\SeoUrl', 'description', 'ACTIVE');
		$dictionaryTypePpcKeywords = $this->createPhraseType('\Seo\SeoUrl', 'ppcKeywords', 'ACTIVE');

		$locationLocalityType = \Service\Location\Type::getBySlug('locality');
		$locationRegionType = \Service\Location\Type::getBySlug('region');
		$locationCountryType = \Service\Location\Type::getBySlug('country');

		$tagType = \Service\Rental\AmenityType::getBySlug('tag');
		debug(mysql_num_rows($r));
		while($x = mysql_fetch_array($r)) {
			$seoUrl = \Service\Seo\SeoUrl::get();
			$seoUrl->oldId = $x['id'];

			// Location
			if ($x['locality_id'] > 0) {
				$location = \Service\Location\Location::getByTypeAndOldId($locationLocalityType, $x['locality_id']);
			} else if ($x['region_id'] > 0) {
				$location = \Service\Location\Location::getByTypeAndOldId($locationRegionType, $x['region_id']);
			} else {
				$location = \Service\Location\Location::getByTypeAndOldId($locationCountryType, $x['country_id']);
			}
			if ($location) {
				$seoUrl->location = $location;
			}

			// Tag
			if ($x['tag_id'] > 0) {
				$tag = \Service\Rental\Amenity::getByTypeAndOldId($tagType, $x['tag_id']);
				if ($tag) {
					$seoUrl->tag = $tag;
				}
			}

			// Attraction Type
			if ($x['attraction_type_id'] > 0) {
				$attractionType = \Service\Attraction\Type::getByOldId($x['attraction_type_id']);
				if ($attractionType) {
					$seoUrl->attractionType = $attractionType;
				}
			}

			// Media
			$temp = array_unique(array_filter(explode(',', $x['photos'])));
			if (is_array($temp) && count($temp)) {
				if ($this->developmentMode == TRUE) $temp = array_slice($temp, 0, 3);
				foreach ($temp as $key => $value) {
					$medium = \Service\Medium\Medium::getByOldUrl('http://www.tralandia.com/u/'.$value);
					if (!$medium) {
						$medium = \Service\Medium\Medium::get();
						if ($medium) {
							$seoUrl->addMedium($medium);
							$medium->setContentFromUrl('http://www.tralandia.com/u/'.$value);
						}
					} else {
						$seoUrl->addMedium($medium);
					}
				}
			}

			$titlePhrase = \Service\Dictionary\Phrase::get();
			$titlePhrase->type = $dictionaryTypeTitle;
			if (isset($languagesByOldId[$x['source_language_id']])) {
				$titlePhrase->sourceLanguage = \Service\Dictionary\Language::get($languagesByOldId[$x['source_language_id']]);
			}

			$headingPhrase = \Service\Dictionary\Phrase::get();
			$headingPhrase->type = $dictionaryTypeHeading;
			if (isset($languagesByOldId[$x['source_language_id']])) {
				$headingPhrase->sourceLanguage = \Service\Dictionary\Language::get($languagesByOldId[$x['source_language_id']]);
			}

			$tabNamePhrase = \Service\Dictionary\Phrase::get();
			$tabNamePhrase->type = $dictionaryTypeTabName;
			if (isset($languagesByOldId[$x['source_language_id']])) {
				$tabNamePhrase->sourceLanguage = \Service\Dictionary\Language::get($languagesByOldId[$x['source_language_id']]);
			}

			$descriptionPhrase = \Service\Dictionary\Phrase::get();
			$descriptionPhrase->type = $dictionaryTypeDescription;
			if (isset($languagesByOldId[$x['source_language_id']])) {
				$descriptionPhrase->sourceLanguage = \Service\Dictionary\Language::get($languagesByOldId[$x['source_language_id']]);
			}

			$r1 = q('select * from seo_urls_texts where seo_url_id = '.$x['id'].' and length(description)>0');
			while ($x1 = mysql_fetch_array($r1)) {

				// Title
				$t = \Service\Dictionary\Translation::get();
				$t->language = \Service\Dictionary\Language::getByOldId($x1['language_id']);
				$t->translation = $x1['title'];
				$t->setPhrase($titlePhrase);
				$t->save();
				$titlePhrase->addTranslation($t);

				// Heading
				$t = \Service\Dictionary\Translation::get();
				$t->language = \Service\Dictionary\Language::getByOldId($x1['language_id']);
				$t->translation = $x1['h1'];
				$t->setPhrase($headingPhrase);
				$t->save();
				$headingPhrase->addTranslation($t);

				// Tab Heading
				$t = \Service\Dictionary\Translation::get();
				$t->language = \Service\Dictionary\Language::getByOldId($x1['language_id']);
				$t->translation = $x1['tab_name'];
				$t->setPhrase($tabNamePhrase);
				$t->save();
				$tabNamePhrase->addTranslation($t);

				// Description
				$t = \Service\Dictionary\Translation::get();
				$t->language = \Service\Dictionary\Language::getByOldId($x1['language_id']);
				$t->translation = $x1['description'];
				$t->setPhrase($descriptionPhrase);
				$t->save();
				$descriptionPhrase->addTranslation($t);
			}

			$seoUrl->title = $titlePhrase;
			$seoUrl->heading = $headingPhrase;
			$seoUrl->tabName = $tabNamePhrase;
			$seoUrl->description = $descriptionPhrase;
			//debug($titlePhrase->getMainEntity()->translations);
			//debug($descriptionPhrase->getMainEntity()->translations); return;
			$seoUrl->save();
		}
		\Extras\Models\Service::flush(FALSE);
	}


}