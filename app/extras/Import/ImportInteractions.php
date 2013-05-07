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

		$r = q('select * from visitors_contact_object order by id limit '.$this->presenter->getParameter('limit'));


		while($x = mysql_fetch_array($r)) {
			$t = $this->context->rentalRepositoryAccessor->get()->findOneByOldId($x['object_id']);
			if (!$t) {
				continue;
			}

			$interaction = $this->context->userRentalReservationEntityFactory->create();
			if (isset($this->languagesByOldId[$x['language']])) {
				$interaction->language = $this->context->languageRepositoryAccessor->get()->find($this->languagesByOldId[$x['language']]);			
			}

				$interaction->rental = $t;
			$interaction->senderEmail = $x['email'];
			$interaction->senderName = $x['name'];

			if (strlen($x['phone'])) {
				if ($tempPhone = $this->context->phoneBook->getOrCreate($x['phone'])) {
					$interaction->senderPhone = $tempPhone;
				}	
			}

			$interaction->arrivalDate = fromStamp($x['date_from']);
			$interaction->departureDate = fromStamp($x['date_to']);

			$interaction->adultsCount = (int)$x['people'];
			$interaction->childrenCount = (int)$x['children'];

			$interaction->message = $x['details'];

			$interaction->oldId = $x['id'];
			//debug($interaction); return;

			$interaction->created = fromStamp($x['stamp']);
			
			$this->model->persist($interaction);
		}
		$this->model->flush();

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
			$interaction = $this->context->userRentalToFriendEntityFactory->create();
			$interaction->language = $this->context->languageRepositoryAccessor->get()->find($this->languagesByOldId[$x['language']]);
			$t = $this->context->rentalRepositoryAccessor->get()->findOneByOldId($x['object_id']);
			if ($t) {
				$interaction->rental = $t;
			}
			$interaction->senderEmail = $x['email_from'];
			$interaction->receiverEmail = $x['email_to'];

			$interaction->message = $x['message'];

			$interaction->oldId = $x['id'];

			$interaction->created = fromStamp($x['stamp']);
			$this->model->persist($interaction);
		}
		$this->model->flush();

		return TRUE;
	}

	public function importSiteReviews() {
		if ($this->developmentMode == TRUE) {
			$r = q('select * from testimonials limit 100');
		} else {
			$r = q('select * from testimonials order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$interaction = $this->context->userSiteReviewRepositoryAccessor->get()->createNew(FALSE);
			$interaction->language = $this->context->languageRepositoryAccessor->get()->find($this->languagesByOldId[$x['language_id']]);
			$interaction->primaryLocation = $this->context->locationRepositoryAccessor->get()->find($this->locationsByOldId[$x['country_id']]);

			if ($x['from_type'] == 'client') {
				$t = $this->context->userRepositoryAccessor->get()->findOneByLogin($x['from_email']);
				$t = $this->context->rentalRepositoryAccessor->get()->findOneByUser($t);
				if ($t) {
					$interaction->rental = $t;
				}
			}


			$interaction->senderEmail = $x['from_email'];
			$interaction->senderName = $x['from_name'];

			$interaction->testimonial = $x['testimonial'];
			$interaction->status = $x['live'] == 1 ? $interaction::STATUS_APPROVED : $interaction::STATUS_PENDING;

			$interaction->oldId = $x['id'];

			$interaction->created = fromStamp($x['date_added']);
			$this->model->persist($interaction);
		}
		$this->model->flush();
		return TRUE;
	}

}