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

class ImportCurrencies extends BaseImport {

	public function doImport($subsection = NULL) {
		$dictionaryType = $this->createPhraseType('\Currency', 'name', 'ACTIVE');

		$r = q('select * from currencies order by id');
		while($x = mysql_fetch_array($r)) {
			$e = $this->context->currencyEntityFactory->create();
			$e->oldId = $x['id'];
			$e->iso = $x['iso'];
			$e->name = $this->createNewPhrase($dictionaryType, $x['name_dic_id']);
			$e->exchangeRate = $x['exchange_rate'];
			$e->rounding = $x['decimal_places'];
			$this->model->persist($e);
		}
		$this->model->flush();
		$this->savedVariables['importedSections']['currencies'] = 1;

	}

}