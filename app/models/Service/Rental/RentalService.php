<?php

namespace Service\Rental;

use Service, Doctrine, Entity;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;


class RentalService extends Service\BaseService
{
	protected $rentalRepositoryAccessor;
	protected $rentalInformationRepositoryAccessor;


	public function injectRepository(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
		$this->rentalInformationRepositoryAccessor = $dic->rentalInformationRepositoryAccessor;
	}

	public function isFeatured() {
		return (bool)$this->rentalRepositoryAccessor->get()->isFeatured($this->entity);
	}

	public function getInterviewAnswers(\Entity\Language $language) {

		$interviews = array();
		foreach ($this->entity->interviewAnswers as $key => $answer) {
			if ($answer->answer->hasTranslationText($language)) {
				$interviews[] = $answer;
			}
		}

		return $interviews;

	}

	# @todo vyclenit positanie ranku do zvlast calssi
	public function calculateRank() {
		/** @var $r \Entity\Rental\Rental */
		$r = $this->entity;

		$conditionalCompulsoryInformation = array('price');
		$pricesCompulsory = !$r->pricesUponRequest;

		$rank = array(
			'points' => 0,
			'complete' => array(),
			'missing' => array(),
			'status' => NULL,
		);

		// Primary location
		if ($r->getPrimaryLocation()) {
			$rank['points'] += 1;
			$rank['complete'][] = 'primaryLocation';
		} else {
			$rank['missing'][] = 'primaryLocation';
		}

		// Rental Type
		if ($r->type) {
			$rank['points'] += 1;
			$rank['complete'][] = 'type';
		} else {
			$rank['missing'][] = 'type';
		}

		// Rental Name
		$name = $r->name;
		if ($name->getValidTranslationsCount() > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'name';
		} else {
			$rank['missing'][] = 'name';
		}

		// Teaser
		$teaser = $r->teaser;
		if ($teaser->getValidTranslationsCount() > 0) {
			$rank['points'] += 3;
			$rank['complete'][] = 'teaser';
		} else {
			$rank['missing'][] = 'teaser';
		}

		// Interview Answers
		$t = 0;
		foreach ($r->interviewAnswers as $value) {
			if ($value->answer->getValidTranslationsCount() > 0) {
				$t++;
			}
		}

		if ($t > 0) {
			$rank['points'] += $t;
			$rank['complete'][] = 'interviewAnswers';
		} else {
			$rank['missing'][] = 'interviewAnswers';
		}

		// Address -> GPS
		$gps = $r->address->getGps();
		if ($gps->latitude !== NULL && $gps->longitude !== NULL) {
			$rank['points'] += 4;
			$rank['complete'][] = 'addressGps';
		} else {
			$rank['missing'][] = 'addressGps';
		}

		// Address -> Locality
		if ($r->address->locality instanceof \Entity\Location\Location) {
			$rank['points'] += 1;
			$rank['complete'][] = 'addressLocality';
		} else {
			$rank['missing'][] = 'addressLocality';
		}

		// Address -> Address
		if (Strings::length($r->address->address) > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'addressAddress';
		} else {
			$rank['missing'][] = 'addressAddress';
		}

		// Address -> Postal Code
		if (Strings::length($r->address->postalCode) > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'addressPostalCode';
		} else {
			$rank['missing'][] = 'addressPostalCode';
		}

		// Contact Name
		if (Strings::length($r->contactName) > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'contactName';
		} else {
			$rank['missing'][] = 'contactName';
		}

		// Contact Phones
		if ($r->phone) {
			$rank['points'] += 2;
			$rank['complete'][] = 'phone';
		} else {
			$rank['missing'][] = 'phone';
		}

		// Contact Emails
		if ($r->email) {
			$rank['points'] += 1;
			$rank['complete'][] = 'email';
		} else {
			$rank['missing'][] = 'email';
		}

		// Contact Url
		if ($r->url) {
			$rank['points'] += 1;
			$rank['complete'][] = 'url';
		} else {
			$rank['missing'][] = 'url';
		}

		// Amenities
		$t = count($r->amenities);
		if ($t > 0) {
			$rank['points'] += ($t > 25 ? 25 : $t);
			$rank['complete'][] = 'amenities';
		} else {
			$rank['missing'][] = 'amenities';
		}

		// Spoken Languages
		$t = count($r->spokenLanguages);
		if ($t > 0) {
			$rank['points'] += ($t > 3 ? 3 : $t);
			$rank['complete'][] = 'spokenLanguages';
		} else {
			$rank['missing'][] = 'spokenLanguages';
		}

		// Check In
		if ($r->checkIn) {
			$rank['points'] += 1;
			$rank['complete'][] = 'checkIn';
		} else {
			$rank['missing'][] = 'checkIn';
		}

		// Check Out
		if ($r->checkOut) {
			$rank['points'] += 1;
			$rank['complete'][] = 'checkOut';
		} else {
			$rank['missing'][] = 'checkOut';
		}

		// Max Capacity
		if ($r->maxCapacity > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'maxCapacity';
		} else {
			$rank['missing'][] = 'maxCapacity';
		}

		// Prices
		if ($r->getPrice()->getSourceAmount() > 0) {
			$rank['points'] += 3;
			$rank['complete'][] = 'price';
		} else {
			if ($pricesCompulsory) {
				$rank['missing'][] = 'price';
			} else {
				$rank['missing'][] = 'price';
			}
		}

		// Classification
		if ($r->classification !== NULL) {
			$rank['points'] += 1;
			$rank['complete'][] = 'classification';
		} else {
			$rank['missing'][] = 'classification';
		}

		//Prices - //@todo
		$t = count($r->pricelists);
		if ($t > 0) {
			$rank['points'] += ($t > 5 ? 5 : $t)*5;
			$rank['complete'][] = 'pricelists';
		} else {
			if ($pricesCompulsory) {
				$rank['missing'][] = 'pricelists';
			} else {
				$rank['missing'][] = 'pricelists';
			}
		}

		//PricelistRows
		$t = count($r->pricelistRows);
		if ($t > 0) {
			$rank['points'] += ($t > 5 ? 5 : $t)*5;
			$rank['complete'][] = 'pricelistRows';
		} else {
			if ($pricesCompulsory) {
				$rank['missing'][] = 'pricelistRows';
			} else {
				$rank['missing'][] = 'pricelistRows';
			}
		}



		// Images
		$t = count($r->images)*2;
		if ($t > 0) {
			$rank['points'] += ($t > 40 ? 40 : $t);
			$rank['complete'][] = 'images';
		} else {
			$rank['missing'][] = 'images';
		}

		// Rooms
		if (Strings::length($r->rooms) > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'rooms';
		} else {
			$rank['missing'][] = 'rooms';
		}

		// Bedroom count
		if ($r->bedroomCount > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'bedroomCount';
		} else {
			$rank['missing'][] = 'bedroomCount';
		}

		// Update Rental
		$rank['status'] = \Entity\Rental\Rental::STATUS_LIVE;
		foreach ($r->missingInformation as $key => $value) {
			$r->removeMissingInformation($value);
		}

		foreach ($rank['missing'] as $key => $value) {
			$information = $this->rentalInformationRepositoryAccessor->get()->findOneBySlug($value);
			if (!$information) {
				throw new \Exception("Rental::CalculateRank - MissingInformation type does not exist: ".$value, 1);
			}
			$r->addMissingInformation($information);
			if ((in_array($value, $conditionalCompulsoryInformation) && $pricesCompulsory) || $information->compulsory) {
				$rank['status'] = \Entity\Rental\Rental::STATUS_DRAFT;
			}
		}

		$r->status = $rank['status'];
		$r->rank = $rank['points'];

		return $rank;
	}

}
