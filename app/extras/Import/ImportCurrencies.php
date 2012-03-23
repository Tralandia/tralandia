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

class ImportCurrencies extends BaseImport {

	public function doImport() {
		$this->savedVariables['importedSections']['currencies'] = 1;
		$dictionaryType = $this->createDictionaryType('\Currency', 'name', 'supportedLanguages', 'ACTIVE');

		$r = q('select * from currencies order by id');
		while($x = mysql_fetch_array($r)) {
			$s = S\CurrencyService::get();
			$s->oldId = $x['id'];
			$s->iso = $x['iso'];
			$s->name = $this->createNewPhrase($dictionaryType, $x['name_dic_id']);
			$s->exchangeRate = $x['exchange_rate'];
			$s->decimalPlaces = $x['decimal_places'];
			$s->rounding = $x['decimal_places'];

			$s->save();
		}
		$this->savedVariables['importedSections']['currencies'] = 2;

	}

}