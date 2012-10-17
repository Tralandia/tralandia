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

class ImportEmailing extends BaseImport {

	public function doImport($subsection = NULL) {

		$import = new \Extras\Import\BaseImport();

		$import->undoSection('emailing');
		//return;

		$this->countryTypeId = qNew('select id from location_type where slug = "country"');
		$this->countryTypeId = mysql_fetch_array($this->countryTypeId);
		$this->locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$this->countryTypeId[0]);

		$this->languagesByOldId = getNewIdsByOld('\Language');

		$subjectType = $this->createPhraseType('\Emailing\Template', 'subject', 'ACTIVE');
		$bodyType = $this->createPhraseType('\Emailing\Template', 'body', 'ACTIVE');

		$templateType = \Service\Emailing\TemplateType::get();
		$templateType->name = 'default';
		$templateType->save();

		$r = q('select * from emails');
		while($x = mysql_fetch_array($r)) {
			$template = \Service\Emailing\Template::get();
			$template->type = $templateType;
			$template->name = $x['name'];
			$template->subject = $this->createNewPhrase($subjectType, $x['subject_dic_id']);
			$template->body = $this->createNewPhrase($bodyType, $x['body_html_dic_id']);
			$template->language = \Service\Dictionary\Language::get($this->languagesByOldId[$x['source_language_id']]);
			$template->oldId = $x['id'];
			//debug($template); return;
			$template->save();
		}
		$this->savedVariables['importedSections']['emailing'] = 1;		
	}
}