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
		$dictionaryType = $this->createDictionaryType('\Currency', 'name', 'ACTIVE');

		$r = q('select * from currencies order by id');
		while($x = mysql_fetch_array($r)) {
			$s = S\Currency::get();
			$s->oldId = $x['id'];
			$s->iso = $x['iso'];
			$s->name = $this->createNewPhrase($dictionaryType, $x['name_dic_id']);
			$s->exchangeRate = $x['exchange_rate'];
			$s->rounding = $x['decimal_places'];
			$s->save();
		}
		$this->savedVariables['importedSections']['currencies'] = 1;

	}

}