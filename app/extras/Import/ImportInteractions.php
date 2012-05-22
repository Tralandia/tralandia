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

class ImportInteractions extends BaseImport {

	public function doImport($subsection = NULL) {

		$import = new \Extras\Import\BaseImport();
		$import->undoSection('interactions');

		$this->countryTypeId = qNew('select id from location_type where slug = "country"');
		$this->countryTypeId = mysql_fetch_array($this->countryTypeId);
		$locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$this->countryTypeId[0]);


		$languagesByOldId = getNewIdsByOld('\Dictionary\Language');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from visitors_contact_object limit 100');
		} else {
			$r = q('select * from visitors_contact_object order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$interaction = \Service\User\RentalReservation::get();
			$interaction->language = \Service\Dictionary\Language::get($languagesByOldId[$x['language']]);
			$t = \Service\Rental\Rental::getByOldId($x['object_id']);
			if ($t) {
				$interaction->rental = $t;
			}
			$interaction->senderEmail = new \Extras\Types\Email($x['email']);
			$interaction->senderName = $x['name'];
			$interaction->senderPhone = new \Extras\Types\Phone(Strings::fixEncoding($x['phone']));
			$interaction->arrivalDate = fromStamp($x['date_from']);
			$interaction->departureDate = fromStamp($x['date_to']);
			$interaction->capacity = array(
				'version' => 1,
				'peopleCount' => $x['people'],
				'childrenCount' => $x['children'],
			);
			$interaction->message = $x['details'];

			$interaction->oldId = $x['id'];
			//debug($interaction); return;

			$interaction->save();
		}

		$this->savedVariables['importedSections']['interactions'] = 1;
	}
}