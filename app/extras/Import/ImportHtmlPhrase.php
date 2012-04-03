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

class ImportHtmlPhrase extends BaseImport {

	public function doImport($oldPhraseId) {
		$dictionaryType = $this->createDictionaryType('Html', 'Html', 'supportedLanguages', 'MARKETING');

		$oldPhraseData = qf('select * from dictionary where id = '.$oldPhraseId);
		$newPhrase = $this->createNewPhrase($dictionaryType, $oldPhraseId);

		$details = array(
			'translationHelp' => $oldPhraseData['help'],
		);
		$newPhrase->details = $details;
		$newPhrase->save();
		return $newPhrase;
	}

}