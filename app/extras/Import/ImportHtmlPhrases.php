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

class ImportHtmlPhrases extends BaseImport {

	public function doImport() {

		$dictionaryType = $this->createDictionaryType('Html', 'Html', 'supportedLanguages', 'MARKETING');

		$r = q('select * from dictionary where text_type = 2');
		$i = 0;
		while ($x = mysql_fetch_array($r)) {
			$newPhrase = $this->createNewPhrase($dictionaryType, $x['id']);

			$details = array(
				'translationHelp' => $x['help'],
			);
			$newPhrase->details = $details;
			$newPhrase->save();
			$i++;
		}
		debug($i);
	}

}