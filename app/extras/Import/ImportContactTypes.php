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
	Service\Log\Change as SLog;

class ImportContactTypes extends BaseImport {

	public function doImport($subsection = NULL) {

		$language = \Service\Dictionary\Language::getByIso('en');

		$s = S\Contact\Type::get();
		$s->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'Address', $language);;
		$s->slug = 'Address';
		$s->save();

		$s = S\Contact\Type::get();
		$s->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'Email', $language);;
		$s->slug = 'Email';
		$s->save();

		$s = S\Contact\Type::get();
		$s->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'Phone', $language);;
		$s->slug = 'Phone';
		$s->save();

		$s = S\Contact\Type::get();
		$s->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'Url', $language);;
		$s->slug = 'Url';
		$s->save();

		$s = S\Contact\Type::get();
		$s->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'Skype', $language);;
		$s->slug = 'Skype';
		$s->save();

		$this->savedVariables['importedSections']['contactTypes'] = 1;

	}
}