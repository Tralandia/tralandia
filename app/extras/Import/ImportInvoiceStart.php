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

class ImportInvoiceStart extends BaseImport {

	public function doImport($subsection = NULL) {

		$context = $this->context;
		$model = $this->model;

		$en = $context->languageRepositoryAccessor->get()->findOneByIso('en');

		$phrase = $this->createPhraseType('\Invoice\UseType', 'name', 'ACTIVE');
		$model->persist($phrase);
		$model->flush();


		$currenciesByOldId = getNewIdsByOld('\Currency');

		// Durations
		$durationNameType = $this->createPhraseType('\Invoice\ServiceDuration', 'name', 'ACTIVE');
		$r = q('select * from invoicing_durations order by id');
		while($x = mysql_fetch_array($r)) {
			$duration = $context->invoiceServiceDurationEntityFactory->create();
			$duration->oldId = $x['id'];
			$duration->duration = $x['strtotime'];
			$duration->sort = $x['sort'];
			$duration->name = $this->createNewPhrase($durationNameType, $x['name_dic_id']);
			$model->persist($duration);
		}

		// Service Types
		$serviceTypeNameType = $this->createPhraseType('\Invoice\ServiceType', 'name', 'ACTIVE');
		$r = q('select * from invoicing_services_types order by id');
		while($x = mysql_fetch_array($r)) {
			$serviceType = $context->invoiceServiceTypeEntityFactory->create();
			$serviceType->oldId = $x['id'];
			$serviceType->slug = $x['name'];
			$serviceType->name = $this->createNewPhrase($serviceTypeNameType, $x['name_dic_id']);
			$model->persist($serviceType);
		}

		// Uses
		$useTypes = array(
			'registration' => 'Registration',
			'member' => 'Adding new rental',
			'recurrence' => 'Recurrence',
			'admin' => 'Internal (for admins / managers)',
			'objectProlong' => 'Prolong object for FREE',
		);

		foreach ($useTypes as $key => $value) {
			$useType = $context->invoiceUseTypeEntityFactory->create();
			$useType->name = $this->createPhraseFromString('\Invoice\UseType', 'name', 'ACTIVE', $value, $en);
			$useType->slug = $key;
			$model->persist($useType);
		}

		$model->flush();

		// Packages
		// Service Types
		$packageNameType = $this->createPhraseType('\Invoice\Package', 'name', 'ACTIVE');
		$packageTeaserType = $this->createPhraseType('\Invoice\Package', 'teaser', 'ACTIVE');
		$countryType = $context->locationRepositoryAccessor->get()->findOneBySlug('country');

		$r = q('select * from invoicing_packages order by id');
		while($x = mysql_fetch_array($r)) {
			$package = $context->invoicePackageEntityFactory->create();
			$package->name = $this->createNewPhrase($packageNameType, $x['name_dic_id']);
			$package->teaser = $this->createNewPhrase($packageTeaserType, $x['teaser_dic_id']);

			$t = $context->currencyRepositoryAccessor->get()->findOneByOldId((int)$x['currencies_id']);

			$package->currency = $t;

			$temp = $context->locationRepositoryAccessor->get()->findOneBy(array('type'=>$countryType, 'oldId'=>$x['countries_id']));
			if ($temp) $package->country = $temp;

			$temp = array_unique(array_filter(explode(',', $x['package_type'])));
			foreach ($temp as $key => $value) {
				$use = $context->invoiceUseTypeRepositoryAccessor->get()->findOneBySlug($value);
				if ($use) {
					$package->addUse($use);
				}
			}

			$r1 = q('select * from invoicing_packages_services where packages_id = '.$x['id'].' order by id');
			while($x1 = mysql_fetch_array($r1)) {
				$packageService = $context->invoiceServiceEntityFactory->create();
				$packageService->type = $context->invoiceServiceTypeRepositoryAccessor->get()->findOneByOldId($x1['services_types_id']);
				$packageService->duration = $context->invoiceServiceDurationRepositoryAccessor->get()->findOneByOldId($x1['duration']);
				$packageService->defaultPrice = (float)$x1['price_default']; 
				$packageService->currentPrice = (float)$x1['price_current'];
				$package->addService($packageService);
			}
			d($package);

			$model->persist($package);
		}
		$model->flush();

		// Marketings
/*		$marketingNameType = $this->createPhraseType('\Invoice\Marketing', 'name', 'ACTIVE');
		$marketingDescriptionType = $this->createPhraseType('\Invoice\Marketing', 'description', 'ACTIVE');
		$r = q('select * from invoicing_marketings order by id');
		while($x = mysql_fetch_array($r)) {
			$marketing = $context->invoiceMarketingEntityFactory->create();
			$marketing->oldId = $x['id'];
			$marketing->name = $this->createNewPhrase($marketingNameType, $x['name_dic_id']);
			$marketing->description = $this->createNewPhrase($marketingDescriptionType, $x['description_dic_id']);
			$temp = $context->invoicePackageRepositoryAccessor->get()->findOneByOldId($x['packages_id']);
			if ($temp) $marketing->package = $temp;
			
			$temp = array_unique(array_filter(explode(',', $x['countries_ids_included'])));
			foreach ($temp as $key => $value) {
				$country = $context->locationRepositoryAccessor->get()->findOneBy(array('type'=>$countryType, 'oldId'=>$value));
				if ($country) {
					$marketing->addLocation($country);
				}
			}
			$marketing->countTotal = $x['count_total'];
			$marketing->countLeft = $x['count_left'];
			$marketing->validFrom = fromStamp($x['time_from']);
			$marketing->validTo = fromStamp($x['time_to']);
			$temp = $context->invoiceUseTypeRepositoryAccessor->get()->findOneBySlug($x['marketing_type']);
			if ($temp) $marketing->addUse($temp);
			$model->persist($marketing);
		}

		$model->flush();
*/
	}

}