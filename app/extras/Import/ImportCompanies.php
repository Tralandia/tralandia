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

class ImportCompanies extends BaseImport {

	public function doImport() {
		$this->savedVariables['importedSections']['companies'] = 1;
		$r = q('select * from companies order by id');

		$dictionaryTypeRegistrator = $this->createDictionaryType('\Company\Company', 'registrator', 'supportedLanguages', 'ACTIVE');

		while($x = mysql_fetch_array($r)) {
			$s = S\Company\CompanyService::get();
			$s->oldId = $x['id'];
			$s->name = $x['name'];

			$s->address = new \Extras\Types\Address(array(
				'address' => array_filter(array($x['address'], $x['address2'])),
				'postcode' => $x['postcode'],
				'locality' => $x['locality'],
				'country' => getByOldId('\Location\Location', $x['locality']),
			));
			$s->companyId = $x['company_id'];
			$s->companyVatId = $x['company_vat_id'];
			$s->vat = $x['vat'];
			$s->registrator = $this->createNewPhrase($dictionaryTypeRegistrator, $x['registrator_dic_id']);

			$countries = getNewIds('\Company\Company', $x['for_countries_ids']);
			foreach ($countries as $key => $value) {
				$s->addCountry(S\Location\LocationService::get($value));
			}

			$s->save();
		}
	
		$this->savedVariables['importedSections']['companies'] = 2;

	}

}