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

class ImportInvoicingStart extends BaseImport {

	public function doImport($subsection = NULL) {

		$en = \Service\Dictionary\Language::getByIso('en');

		// Durations
		$durationNameType = $this->createDictionaryType('\Invoicing\Service\Duration', 'name', 'supportedLanguages', 'ACTIVE');
		$r = q('select * from invoicing_durations order by id');
		while($x = mysql_fetch_array($r)) {
			$duration = \Service\Invoicing\Service\Duration::get();
			$duration->oldId = $x['id'];
			$duration->duration = $x['strtotime'];
			$duration->sort = $x['sort'];
			$duration->name = $this->createNewPhrase($durationNameType, $x['name_dic_id']);
			$duration->save();
		}

		// Service Types
		$serviceTypeNameType = $this->createDictionaryType('\Invoicing\Service\Type', 'name', 'supportedLanguages', 'ACTIVE');
		$r = q('select * from invoicing_services_types order by id');
		while($x = mysql_fetch_array($r)) {
			$serviceType = \Service\Invoicing\Service\Duration::get();
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
			$useType->name = $this->createPhraseFromString('\Invoicing\UseType', 'name', 'supportedLanguages', 'ACTIVE', $value, $en);
			$useType->slug = $key;
			$useType->save();
		}

		\Extras\Models\Service::flush(FALSE);

		// Packages
		// Service Types
		$packageNameType = $this->createDictionaryType('\Invoicing\Package', 'name', 'supportedLanguages', 'ACTIVE');
		$packageTeaserType = $this->createDictionaryType('\Invoicing\Package', 'teaser', 'supportedLanguages', 'ACTIVE');
		$countryType = \Service\Location\Type::getBySlug('country');

		$r = q('select * from invoicing_packages order by id');
		while($x = mysql_fetch_array($r)) {
			$package = \Service\Invoicing\Package::get();
			$package->name = $this->createNewPhrase($packageNameType, $x['name_dic_id']);
			$package->teaser = $this->createNewPhrase($packageTeaserType, $x['teaser_dic_id']);
			$package->country = \Service\Location\Location::getByTypeAndOldId($countryType, $x['countries_id']);
			$temp = array_unique(array_filter(explode(',', $x['package_type'])));
			foreach ($temp as $key => $value) {
				$use = \Invoicing\UseType::getBySlug($value);
				if ($use) {
					$package->addUse($use);
				}
			}

			$r1 = q('select * from invoicing_packages_services where packages_id = '.$x['id'].' order by id');
			while($x1 = mysql_fetch_array($r1)) {
				$packageService = \Service\Invoicing\Service\Service::get();
				$packageService->type = \Service\Invoicing\Service\Type::getByOldId($x1['services_types_id']);
				$packageService->duration = \Invoicing\Service\Duration::getByOldId($x1['duration']);
				$packageService->defaultPrice = new \Extras\Types\Price(); //@todo - ako toto ? currency je pri package
				$packageService->currentPrice = new \Extras\Types\Price(); //@todo - ako toto ? currency je pri package
				$packageService->save();
				$package->addService($packageService);
			}
			$package->save();
			\Extras\Models\Service::flush(FALSE);
		}

		// Marketings
		$marketingNameType = $this->createDictionaryType('\Invoicing\Marketing', 'name', 'supportedLanguages', 'ACTIVE');
		$marketingDescriptionType = $this->createDictionaryType('\Invoicing\Marketing', 'description', 'supportedLanguages', 'ACTIVE');
		$r = q('select * from invoicing_marketings order by id');
		while($x = mysql_fetch_array($r)) {
			$marketing = \Service\Invoicing\Marketing::get();
			$marketing->oldId = $x['id'];
			$marketing->name = $this->createNewPhrase($marketingNameType, $x['name_dic_id']);
			$marketing->description = $this->createNewPhrase($marketingDescriptionType, $x['description_dic_id']);
			$marketing->package = \Services\Invoicing\Package::getByOldId($x['packages_id']);
			
			$temp = array_unique(array_filter(explode(',', $x['countries_ids_included'])));
			foreach ($temp as $key => $value) {
				$country = \Service\Location\Location::getByTypeAndOldId($countryType, $x['countries_id']);;
				if ($country) {
					$marketing->addLocation($country);
				}
			}
			$marketing->countTotal = $x['count_total'];
			$marketing->countLeft = $x['count_left'];
			$marketing->validFrom = fromStamp($x['time_from']);
			$marketing->validTo = fromStamp($x['time_to']);
			$marketing->addUse(\Service\Invoicing\UseType::getBySlug($x['marketing_type']));
			$marketing->save();
		}

		// Coupons
		// Neimportujeme, lebo sa vobec nepouzivaju...
		
		$this->savedVariables['importedSections']['invoicingStart'] = 1;

	}

}