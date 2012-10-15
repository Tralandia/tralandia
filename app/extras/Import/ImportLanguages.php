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

		$phraseType = $this->createPhraseType('\Language', 'name', 'ACTIVE');
		
		while($x = mysql_fetch_array($r)) {
			$s = $this->context->languageServiceFactory->create();
			$e = $s->getEntity();
			$e->oldId = $x['id'];
			$e->iso = $x['iso'];

			# @todo tu sa vytvoria prazdne frazy (nemaju ziadne Translation)
			$e->name = $this->createNewPhrase($phraseType, $x['name_dic_id']);
			
			$e->supported = (bool)$x['translated'];
			$e->defaultCollation = $x['default_collation'];
			$e->details = explode2Levels(';', ':', $x['attributes']);
			$s->save(FALSE); // prevent flush
		}
		$s->save(); //flush

		$this->savedVariables['importedSections']['languages'] = 1;
	}

}