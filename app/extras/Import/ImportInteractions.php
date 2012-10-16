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

		// $user1 = \Service\User\User::get(1);
		// $user2 = \Service\User\User::get(4);
		// // $user3 = \Service\User\User::get(3);
		// // $user4 = \Service\User\User::get(4);

		// \Service\User\User::merge($user1, $user2);
		// return;

		$this->setSubsections('interactions');

		$this->countryTypeId = qNew('select id from location_type where slug = "country"');
		$this->countryTypeId = mysql_fetch_array($this->countryTypeId);
		$this->locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$this->countryTypeId[0]);

		$this->languagesByOldId = getNewIdsByOld('\Language');

		$this->$subsection();

		$this->savedVariables['importedSubSections']['interactions'][$subsection] = 1;

		if (end($this->sections['interactions']['subsections']) == $subsection) {
			$this->savedVariables['importedSections']['interactions'] = 1;		
		}
	}


	public function importRentalReservations() {

		if ($this->developmentMode == TRUE) {
			$r = q('select * from visitors_contact_object limit 100');
		} else {
			$r = q('select * from visitors_contact_object order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$interaction = \Service\User\RentalReservation::get();
			$interaction->language = \Service\Dictionary\Language::get($this->languagesByOldId[$x['language']]);
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
			$interaction->created = fromStamp($x['stamp']); //@todo - uplne to ignoruje....
			//debug($interaction); return;
			$interaction->save();
		}

		return TRUE;
	}

	public function importRentalQuestions() {
		if ($this->developmentMode == TRUE) {
			$r = q('select * from visitors_questions limit 100');
		} else {
			$r = q('select * from visitors_questions order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$interaction = \Service\User\RentalQuestion::get();
			$interaction->language = \Service\Dictionary\Language::get($this->languagesByOldId[$x['language_id']]);
			$t = \Service\Rental\Rental::getByOldId($x['object_id']);
			if ($t) {
				$interaction->rental = $t;
			}
			$interaction->senderEmail = new \Extras\Types\Email($x['email_from']);
			$interaction->senderPhone = new \Extras\Types\Phone(Strings::fixEncoding($x['phone']));
			$interaction->question = $x['message'];

			$interaction->oldId = $x['id'];

			$interaction->save();
			$interaction->created = fromStamp($x['stamp']);
			$interaction->save();
			//debug($interaction); return;
		}

		return TRUE;
	}

	public function importRentalReviews() {
		// toto vlastne ani nemame :)
	}

	public function importRentalToFriend() {
		if ($this->developmentMode == TRUE) {
			$r = q('select * from visitors_friend limit 100');
		} else {
			$r = q('select * from visitors_friend order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$interaction = \Service\User\RentalToFriend::get();
			$interaction->language = \Service\Dictionary\Language::get($this->languagesByOldId[$x['language']]);
			$t = \Service\Rental\Rental::getByOldId($x['object_id']);
			if ($t) {
				$interaction->rental = $t;
			}
			$interaction->senderEmail = new \Extras\Types\Email($x['email_from']);
			$interaction->receiverEmail = new \Extras\Types\Email($x['email_to']);

			$interaction->message = $x['message'];

			$interaction->oldId = $x['id'];

			$interaction->save();
			$interaction->created = fromStamp($x['stamp']);
			$interaction->save();
			//debug($interaction); return;
		}

		return TRUE;
	}

	public function importSiteOwnerReviews() {
		if ($this->developmentMode == TRUE) {
			$r = q('select * from testimonials where from_type = "client" limit 100');
		} else {
			$r = q('select * from testimonials where from_type = "client" order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$interaction = \Service\User\SiteOwnerReview::get();
			$interaction->language = \Service\Dictionary\Language::get($this->languagesByOldId[$x['language_id']]);
			$interaction->location = \Service\Location\Location::get($this->locationsByOldId[$x['country_id']]);

			$interaction->senderEmail = new \Extras\Types\Email($x['from_email']);
			$interaction->senderName = $x['from_name'];

			$interaction->testimonial = $x['testimonial'];
			$interaction->status = $x['live'] == 1 ? \Entity\User\SiteOwnerReview::STATUS_APROVED : \Entity\User\SiteOwnerReview::STATUS_PENDING;

			$interaction->oldId = $x['id'];

			$interaction->save();
			$interaction->created = fromStamp($x['date_added']);
			$interaction->save();
			//debug($interaction); return;
		}

		return TRUE;
	}

	public function importSiteVisitorReviews() {
		if ($this->developmentMode == TRUE) {
			$r = q('select * from testimonials where from_type = "visitor" limit 100');
		} else {
			$r = q('select * from testimonials where from_type = "visitor" order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$interaction = \Service\User\SiteVisitorReview::get();
			$interaction->language = \Service\Dictionary\Language::get($this->languagesByOldId[$x['language_id']]);
			$interaction->location = \Service\Location\Location::get($this->locationsByOldId[$x['country_id']]);

			$interaction->senderEmail = new \Extras\Types\Email($x['from_email']);
			$interaction->senderName = $x['from_name'];

			$interaction->testimonial = $x['testimonial'];
			$interaction->status = $x['live'] == 1 ? \Entity\User\SiteVisitorReview::STATUS_APROVED : \Entity\User\SiteVisitorReview::STATUS_PENDING;

			$interaction->oldId = $x['id'];

			$interaction->save();
			$interaction->created = fromStamp($x['date_added']);
			$interaction->save();
			//debug($interaction); return;
		}

		return TRUE;
	}
}