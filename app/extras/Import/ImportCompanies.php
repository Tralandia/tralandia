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

class ImportCompanies extends BaseImport {

	public function doImport() {
		$this->savedVariables['importedSections']['companies'] = 1;
	
		//$this->importCompanies();
		//$this->importOffices();
		//$this->importBankAccounts();

		$this->savedVariables['importedSections']['companies'] = 2;

	}

	private function importCompanies() {
		$r = q('select * from companies order by id');

		$dictionaryTypeRegistrator = $this->createDictionaryType('\Company\Company', 'registrator', 'supportedLanguages', 'ACTIVE');

		while($x = mysql_fetch_array($r)) {
			$s = S\Company\Company::get();
			$s->oldId = $x['id'];
			$s->name = $x['name'];

			$s->address = new \Extras\Types\Address(array(
				'address' => array_filter(array($x['address'], $x['address2'])),
				'postcode' => $x['postcode'],
				'locality' => $x['locality'],
				'country' => getByOldId('\Location\Location', $x['countries_id']),
			));
			$s->companyId = $x['company_id'];
			$s->companyVatId = $x['company_vat_id'];
			$s->vat = $x['vat'];
			if ($x['registrator_dic_id'] > 0) {
				$s->registrator = $this->createNewPhrase($dictionaryTypeRegistrator, $x['registrator_dic_id']);				
			}

			$countries = getNewIds('\Company\Company', $x['for_countries_ids']);
			foreach ($countries as $key => $value) {
				$s->addCountry(S\Location\Location::get($value));
			}
			$s->save();
		}
	}

	private function importOffices() {
		$r = q('select * from virtual_offices order by id');

		$dictionaryTypeRegistrator = $this->createDictionaryType('\Company\Company', 'registrator', 'supportedLanguages', 'ACTIVE');

		while($x = mysql_fetch_array($r)) {
			$s = S\Company\Office::get();
			$s->oldId = $x['id'];

			$s->address = new \Extras\Types\Address(array(
				'address' => array_filter(array($x['address'], $x['address_2'])),
				'postcode' => $x['postcode'],
				'locality' => $x['locality'],
				'country' => \Service\Location\Country::getByOldId($x['id'])->location->id,
			)); // @todo - toto este neuklada ok, je na to task v taskee

			$s->company = \Service\Company\Company::get(3);
			$s->addCountry(\Service\Location\Country::getByOldId($x['id'])->location);
			$s->save();
		}
	}

	private function importBankAccounts() {
		$r = q('select * from bank_accounts order by id');

		$dictionaryTypeRegistrator = $this->createDictionaryType('\Company\Company', 'registrator', 'supportedLanguages', 'ACTIVE');

		while($x = mysql_fetch_array($r)) {
			$s = S\Company\BankAccount::get();
			$s->oldId = $x['id'];
			$s->company = \Service\Company\Company::getByOldId($x['companies_id']);
			$s->bankName = $x['bank_name'];
			$s->bankSwift = $x['bank_swift'];

			$s->bankAddress = new \Extras\Types\Address(array(
				'address' => $x['bank_address'],
				'country' => \Service\Location\Country::getByOldId($x['bank_country_id'])->location->id,
			)); // @todo - toto este neuklada ok, je na to task v taskee
			
			$s->accountNumber = $x['account_number'];
			$s->accountName = $x['account_name'];
			$s->accountIban = $x['account_iban'];

			$s->addCountry(\Service\Location\Country::getByOldId($x['bank_country_id'])->location);
			$s->save();
		}
	}

}