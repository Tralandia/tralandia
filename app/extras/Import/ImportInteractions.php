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

	public $locationsByOldId, $languagesByOldId;

	public function doImport($subsection = NULL) {

		$countryTypeId = qNew('select id from location_type where slug = "country"');
		$countryTypeId = mysql_fetch_array($countryTypeId);
		$this->locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$countryTypeId[0]);

		$this->languagesByOldId = getNewIdsByOld('\Language');

		$this->{$subsection}();

	}


	public function importRentalReservations() {

		if ($this->developmentMode == TRUE) {
			$r = q('select * from visitors_contact_object limit 100');
		} else {
			$r = q('select * from visitors_contact_object order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$interaction = $this->context->userRentalReservationEntityFactory->create();
			$interaction->language = $this->context->languageRepositoryAccessor->get()->find($this->languagesByOldId[$x['language']]);
			$t = $this->context->rentalRepositoryAccessor->get()->findOneByOldId($x['object_id']);
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

			$interaction->created = fromStamp($x['stamp']);
			
			$this->model->persist($interaction);
		}
		$this->model->flush();

		return TRUE;
	}

	// public function importRentalQuestions() {
	// 	if ($this->developmentMode == TRUE) {
	// 		$r = q('select * from visitors_questions limit 100');
	// 	} else {
	// 		$r = q('select * from visitors_questions order by id');
	// 	}

	// 	while($x = mysql_fetch_array($r)) {
	// 		$interaction = $this->context->userRentalQuestionEntityFactory->create();
	// 		$interaction->language = $this->context->languageRepositoryAccessor->get()->find($this->languagesByOldId[$x['language_id']]);
	// 		$t = $this->context->rentalRepositoryAccessor->get()->findOneByOldId($x['object_id']);
	// 		if ($t) {
	// 			$interaction->rental = $t;
	// 		}
	// 		$interaction->senderEmail = new \Extras\Types\Email($x['email_from']);
	// 		$interaction->senderPhone = new \Extras\Types\Phone(Strings::fixEncoding($x['phone']));
	// 		$interaction->question = $x['message'];

	// 		$interaction->oldId = $x['id'];

	// 		$interaction->created = fromStamp($x['stamp']);
	// 		$this->model->persist($interaction);
	// 	}
	// 	$this->model->flush();

	// 	return TRUE;
	// }

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
			$interaction = $this->context->userRentalToFriendEntityFactory->create();
			$interaction->language = $this->context->languageRepositoryAccessor->get()->find($this->languagesByOldId[$x['language']]);
			$t = $this->context->rentalRepositoryAccessor->get()->findOneByOldId($x['object_id']);
			if ($t) {
				$interaction->rental = $t;
			}
			$interaction->senderEmail = new \Extras\Types\Email($x['email_from']);
			$interaction->receiverEmail = new \Extras\Types\Email($x['email_to']);

			$interaction->message = $x['message'];

			$interaction->oldId = $x['id'];

			$interaction->created = fromStamp($x['stamp']);
			$this->model->persist($interaction);
		}
		$this->model->flush();

		return TRUE;
	}

	public function importSiteOwnerReviews() {
		if ($this->developmentMode == TRUE) {
			$r = q('select * from testimonials where from_type = "client" limit 100');
		} else {
			$r = q('select * from testimonials where from_type = "client" order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$interaction = $this->context->userSiteOwnerReviewEntityFactory->create();
			$interaction->language = $this->context->languageRepositoryAccessor->get()->find($this->languagesByOldId[$x['language_id']]);
			$interaction->location = $this->context->locationRepositoryAccessor->get()->find($this->locationsByOldId[$x['country_id']]);

			$interaction->senderEmail = new \Extras\Types\Email($x['from_email']);
			$interaction->senderName = $x['from_name'];

			$interaction->testimonial = $x['testimonial'];
			$interaction->status = $x['live'] == 1 ? $interaction::STATUS_APROVED : $interaction::STATUS_PENDING;

			$interaction->oldId = $x['id'];

			$interaction->created = fromStamp($x['date_added']);
			$this->model->persist($interaction);
		}
		$this->model->flush();
		return TRUE;
	}

	public function importSiteVisitorReviews() {
		if ($this->developmentMode == TRUE) {
			$r = q('select * from testimonials where from_type = "visitor" limit 100');
		} else {
			$r = q('select * from testimonials where from_type = "visitor" order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$interaction = $this->context->userSiteOwnerReviewEntityFactory->create();
			$interaction->language = $this->context->languageRepositoryAccessor->get()->find($this->languagesByOldId[$x['language_id']]);
			$interaction->location = $this->context->locationRepositoryAccessor->get()->find($this->locationsByOldId[$x['country_id']]);

			$interaction->senderEmail = new \Extras\Types\Email($x['from_email']);
			$interaction->senderName = $x['from_name'];

			$interaction->testimonial = $x['testimonial'];
			$interaction->status = $x['live'] == 1 ? $interaction::STATUS_APROVED : $interaction::STATUS_PENDING;

			$interaction->oldId = $x['id'];

			$interaction->created = fromStamp($x['date_added']);
			$this->model->persist($interaction);
		}
		$this->model->flush();

		return TRUE;
	}
}