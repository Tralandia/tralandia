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

class ImportPathSegments extends BaseImport {

	const TYPE_PAGE = 2;
	const TYPE_ATTRACTION_TYPE = 4;
	const TYPE_LOCATION = 6;
	const TYPE_RENTAL_TYPE = 8;
	const TYPE_TAG = 10;

	public function doImport($subsection = NULL) {

		$pathSegment = new \Extras\Cron\PathSegments();
		$pathSegment->updatePathsegments();
		return;

		$this->setSubsections('pathsegments');

		$this->countryTypeId = qNew('select id from location_type where slug = "country"');
		$this->countryTypeId = mysql_fetch_array($this->countryTypeId);
		$this->locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$this->countryTypeId[0]);

		$this->languages = array();
		$r = qNew('select id from dictionary_languages order by id');
		while ($x = mysql_fetch_array($r)) {
			$this->languages[] = \Service\Dictionary\Language::get($x['id']);
		}

		$this->$subsection();

		$this->savedVariables['importedSubSections']['pathsegments'][$subsection] = 1;

		if (end($this->sections['pathsegments']['subsections']) == $subsection) {
			$this->savedVariables['importedSections']['pathsegments'] = 1;		
		}
	}

	function saveUrls($data) {
		if(is_array($data) && count($data)) {
			$insert = array();
			foreach($data as $key => $val) {
				if(!strlen($val['pathSegment'])) continue;
				$insert[] = sprintf('(%d, %d, "%s", %d, %d)', $val['country_id'], $val['language_id'], $val['pathSegment'], $val['type'], $val['entityId']);;
			}
			qNew('INSERT INTO routing_pathsegment (country_id, language_id, pathSegment, type, entityId) VALUES '.implode(', ', $insert));
			debug('Pridal som '.count($insert).' zaznamov');
		}
	}

	function getTranslation($phraseId, $languageId, $type = 'translation') {
		$translation = \Service\Dictionary\Translation::getByPhraseAndLanguage();
	}

	public function importPages() {
		//@todo - zatial nespravene
		return TRUE;
	}

}