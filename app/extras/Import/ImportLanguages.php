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

class ImportLanguages extends BaseImport {

	public function doImport($subsection = NULL) {

		$this->undoSection('languages');

		$r = q('select * from languages order by id');
		while($x = mysql_fetch_array($r)) {
			$s = $this->context->languageServiceFactory->create();
			$s->oldId = $x['id'];
			$s->iso = $x['iso'];
			$s->supported = (bool)$x['translated'];
			$s->defaultCollation = $x['default_collation'];
			$s->details = explode2Levels(';', ':', $x['attributes']);
			$s->save(FALSE); // prevent flush
		}
		$s->save(); //flush

		$this->createPhrasesByOld('\Dictionary\Language', 'name', 'ACTIVE', 'languages', 'name_dic_id');		
		$this->savedVariables['importedSections']['languages'] = 1;
	}

}