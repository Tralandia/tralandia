<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Services\Dictionary as D,
	Services as S,
	Services\Log\Change as SLog;

class ImportContactTypes extends BaseImport {

	public function doImport() {
		$this->savedVariables['importedSections']['contactTypes'] = 1;

		$language = getLangByIso('en');

		$s = S\Contact\TypeService::get();
		$s->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'Address', $language);;
		$s->class = 'Address';
		$s->save();

		$s = S\Contact\TypeService::get();
		$s->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'Email', $language);;
		$s->class = 'Email';
		$s->save();

		$s = S\Contact\TypeService::get();
		$s->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'Phone', $language);;
		$s->class = 'Phone';
		$s->save();

		$s = S\Contact\TypeService::get();
		$s->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'Url', $language);;
		$s->class = 'Url';
		$s->save();

		$this->savedVariables['importedSections']['contactTypes'] = 2;

	}

}