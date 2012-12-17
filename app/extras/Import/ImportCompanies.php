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

class ImportCompanies extends BaseImport {

	public function doImport($subsection) {	

		$this->{$subsection}();

	}

	private function importCompanies() {
		$r = q('select * from companies order by id');

		$dictionaryTypeRegistrator = $this->createPhraseType('\Company\Company', 'registrator', 'ACTIVE');

		while($x = mysql_fetch_array($r)) {
			$s = $this->context->companyEntityFactory->create();
			$s->oldId = $x['id'];
			$s->name = $x['name'];

			// $s->address = new \Extras\Types\Address($x['address'], $x['address2'], $x['locality'], $x['postcode'], getByOldId('\Location\Location', $x['countries_id']);

			$s->companyId = $x['company_id'];
			$s->companyVatId = $x['company_vat_id'];
			$s->vat = $x['vat'];
			if ($x['registrator_dic_id'] > 0) {
				$s->registrator = $this->createNewPhrase($dictionaryTypeRegistrator, $x['registrator_dic_id']);				
			}

			// $countries = getNewIds('\Company\Company', $x['for_countries_ids']);
			// d($countries);
			// foreach ($countries as $key => $value) {
			// 	$t = $this->context->locationRepositoryAccessor->get()->find($value);
			// 	$s->addCountry($t);
			// }
			$this->model->persist($s);
		}
		$this->model->flush();
	}

	// private function importOffices() {
	// 	$r = q('select * from virtual_offices order by id');

	// 	$dictionaryTypeRegistrator = $this->createPhraseType('\Company\Company', 'registrator', 'ACTIVE');

	// 	$countryLocationType = $this->context->locationTypeRepositoryAccessor->get()->findOneBySlug('country');

	// 	while($x = mysql_fetch_array($r)) {
	// 		$s = $this->context->companyOfficeEntityFactory->create();
	// 		$s->oldId = $x['id'];

	// 		$locationTemp = $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $x['countries_id'], 'type' => $countryLocationType));
	// 		$s->address = new \Extras\Types\Address(array(
	// 			'address' => array_filter(array($x['address'], $x['address_2'])),
	// 			'postcode' => $x['postcode'],
	// 			'locality' => $x['locality'],
	// 			'country' => $locationTemp->id,
	// 		)); // @todo - toto este neuklada ok, je na to task v taskee

	// 		$s->company = $this->context->companyRepositoryAccessor->get()->find(3);
	// 		$s->addCountry($locationTemp);
	// 		$this->model->persist($s);
	// 	}
	// 	$this->model->flush();
	// }

	private function importBankAccounts() {
		$r = q('select * from bank_accounts order by id');

		$dictionaryTypeRegistrator = $this->createPhraseType('\Company\Company', 'registrator', 'ACTIVE');

		$countryLocationType = $this->context->locationTypeRepositoryAccessor->get()->findOneBySlug('country');

		while($x = mysql_fetch_array($r)) {
			$s = $this->context->companyBankAccountEntityFactory->create();
			$s->oldId = $x['id'];
			$s->company = $this->context->companyRepositoryAccessor->get()->findOneBy(array('oldId' => $x['companies_id']));
			$s->bankName = $x['bank_name'];
			$s->bankSwift = $x['bank_swift'];

			$locationTemp = $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $x['bank_country_id'], 'type' => $countryLocationType));
			$s->bankAddress = $x['bank_address'];
			$s->primaryLocation = $locationTemp;
			
			$s->accountNumber = $x['account_number'];
			$s->accountName = $x['account_name'];
			$s->accountIban = $x['account_iban'];

			$s->addCountry($locationTemp);
			$this->model->persist($s);
		}
		$this->model->flush();
	}

}