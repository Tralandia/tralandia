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

class ImportHtmlPhrases extends BaseImport {

	public function doImport($subsection = NULL) {

		$dictionaryType = $this->createPhraseType('Html', 'Html', 'MARKETING');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from dictionary where text_type = 2');
		} else {
			$r = q('select * from dictionary where text_type = 2');
		}
		$i = 0;
		while ($x = mysql_fetch_array($r)) {
			$newPhrase = $this->createNewPhrase($dictionaryType, $x['id']);

			$details = array(
				'translationHelp' => $x['help'],
			);
			$newPhrase->details = $details;
			$this->context->model->persist($newPhrase);
			$i++;
		}
		$this->context->model->flush();

		$this->savedVariables['importedSections']['htmlPhrases'] = 1;
	}

}