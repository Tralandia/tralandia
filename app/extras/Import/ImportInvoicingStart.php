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

class ImportInvoicingStart extends BaseImport {

	public function doImport($subsection = NULL) {

		$en = \Service\Dictionary\Language::getByIso('en');

		$this->createPhraseType('\Invoicing\UseType', 'name', 'ACTIVE');
		\Extras\Models\Service::flush(FALSE);


		$currenciesByOldId = getNewIdsByOld('\Currency');

		// Durations
		$durationNameType = $this->createPhraseType('\Invoicing\ServiceDuration', 'name', 'ACTIVE');
		$r = q('select * from invoicing_durations order by id');
		while($x = mysql_fetch_array($r)) {
			$duration = \Service\Invoicing\ServiceDuration::get();
			$duration->oldId = $x['id'];
			$duration->duration = $x['strtotime'];
			$duration->sort = $x['sort'];
			$duration->name = $this->createNewPhrase($durationNameType, $x['name_dic_id']);
			$duration->save();
		}

		// Service Types
		$serviceTypeNameType = $this->createPhraseType('\Invoicing\ServiceType', 'name', 'ACTIVE');
		$r = q('select * from invoicing_services_types order by id');
		while($x = mysql_fetch_array($r)) {
			$serviceType = \Service\Invoicing\ServiceType::get();
			$serviceType->oldId = $x['id'];
			$serviceType->slug = $x['name'];
			$serviceType->name = $this->createNewPhrase($serviceTypeNameType, $x['name_dic_id']);
			$serviceType->save();
		}

		// Uses
		$useTypes = array(
			'registration' => 'Registration',
			'member' => 'Adding new rental',
			'recurrence' => 'Recurrence',
			'admin' => 'Internal (for admins / managers)',
			'coupon' => 'Coupons',
			'marketing' => 'Marketings',
			'objectProlong' => 'Prolong object for FREE',
		);

		foreach ($useTypes as $key => $value) {
			$useType = \Service\Invoicing\UseType::get();
			$useType->name = $this->createPhraseFromString('\Invoicing\UseType', 'name', 'ACTIVE', $value, $en);
			$useType->slug = $key;
			$useType->save();
		}

		\Extras\Models\Service::flush(FALSE);

		// Packages
		// Service Types
		$packageNameType = $this->createPhraseType('\Invoicing\Package', 'name', 'ACTIVE');
		$packageTeaserType = $this->createPhraseType('\Invoicing\Package', 'teaser', 'ACTIVE');
		$countryType = \Service\Location\Type::getBySlug('country');

		$r = q('select * from invoicing_packages order by id');
		while($x = mysql_fetch_array($r)) {
			$package = \Service\Invoicing\Package::get();
			$package->name = $this->createNewPhrase($packageNameType, $x['name_dic_id']);
			$package->teaser = $this->createNewPhrase($packageTeaserType, $x['teaser_dic_id']);
			
			$temp = \Service\Location\Location::getByTypeAndOldId($countryType, $x['countries_id']);
			if ($temp) $package->country = $temp;

			$temp = array_unique(array_filter(explode(',', $x['package_type'])));
			foreach ($temp as $key => $value) {
				$use = \Service\Invoicing\UseType::getBySlug($value);
				if ($use) {
					$package->addUse($use);
				}
			}

			$r1 = q('select * from invoicing_packages_services where packages_id = '.$x['id'].' order by id');
			while($x1 = mysql_fetch_array($r1)) {
				$packageService = \Service\Invoicing\Service::get();
				$packageService->type = \Service\Invoicing\ServiceType::getByOldId($x1['services_types_id']);
				$packageService->duration = \Service\Invoicing\ServiceDuration::getByOldId($x1['duration']);
				$packageService->defaultPrice = new \Extras\Types\Price($x1['price_default'], $currenciesByOldId[(int)$x['currencies_id']]); 
				$packageService->currentPrice = new \Extras\Types\Price($x1['price_current'], $currenciesByOldId[(int)$x['currencies_id']]);
				$packageService->save();
				$package->addService($packageService);
			}

			$package->save();
		}
		\Extras\Models\Service::flush(FALSE);

		// Marketings
		$marketingNameType = $this->createPhraseType('\Invoicing\Marketing', 'name', 'ACTIVE');
		$marketingDescriptionType = $this->createPhraseType('\Invoicing\Marketing', 'description', 'ACTIVE');
		$r = q('select * from invoicing_marketings order by id');
		while($x = mysql_fetch_array($r)) {
			$marketing = \Service\Invoicing\Marketing::get();
			$marketing->oldId = $x['id'];
			$marketing->name = $this->createNewPhrase($marketingNameType, $x['name_dic_id']);
			$marketing->description = $this->createNewPhrase($marketingDescriptionType, $x['description_dic_id']);
			$temp = \Service\Invoicing\Package::getByOldId($x['packages_id']);
			if ($temp) $marketing->package = $temp;
			
			$temp = array_unique(array_filter(explode(',', $x['countries_ids_included'])));
			foreach ($temp as $key => $value) {
				$country = \Service\Location\Location::getByTypeAndOldId($countryType, $value);;
				if ($country) {
					$marketing->addLocation($country);
				}
			}
			$marketing->countTotal = $x['count_total'];
			$marketing->countLeft = $x['count_left'];
			$marketing->validFrom = fromStamp($x['time_from']);
			$marketing->validTo = fromStamp($x['time_to']);
			$temp = \Service\Invoicing\UseType::getBySlug($x['marketing_type']);
			if ($temp) $marketing->addUse($temp);
			$marketing->save();
		}

		// Coupons
		// Neimportujeme, lebo sa vobec nepouzivaju...
		
		$this->savedVariables['importedSections']['invoicingStart'] = 1;

	}

}