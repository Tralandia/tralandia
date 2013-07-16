<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/22/13 2:59 PM
 */

namespace Tralandia\Rental;


use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Nette;
use Nette\Utils\Strings;

class RankCalculator {


	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;


	public function __construct(EntityManager $em) {
		$this->em = $em;
	}


	public function updateRank(Rental $rental){

		$conditionalCompulsoryInformation = array('price');
		$pricesCompulsory = !$rental->pricesUponRequest;

		$rank = array(
			'points' => 0,
			'complete' => array(),
			'missing' => array(),
			'status' => NULL,
		);

		// Primary location
		if ($rental->getPrimaryLocation()) {
			$rank['points'] += 1;
			$rank['complete'][] = 'primaryLocation';
		} else {
			$rank['missing'][] = 'primaryLocation';
		}

		// Rental Type
		if ($rental->getType()) {
			$rank['points'] += 1;
			$rank['complete'][] = 'type';
		} else {
			$rank['missing'][] = 'type';
		}

		// Rental Name
		$name = $rental->getName();
		if ($name->getValidTranslationsCount() > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'name';
		} else {
			$rank['missing'][] = 'name';
		}

		// Teaser
		$teaser = $rental->getTeaser();
		if ($teaser->getValidTranslationsCount() > 0) {
			$rank['points'] += 3;
			$rank['complete'][] = 'teaser';
		} else {
			$rank['missing'][] = 'teaser';
		}

		// Interview Answers
		$t = 0;
		foreach ($rental->getInterviewAnswers() as $value) {
			if ($value->getAnswer()->getValidTranslationsCount() > 0) {
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
		$gps = $rental->getAddress()->getGps();
		if ($gps->latitude !== NULL && $gps->longitude !== NULL) {
			$rank['points'] += 4;
			$rank['complete'][] = 'addressGps';
		} else {
			$rank['missing'][] = 'addressGps';
		}

		// Address -> Locality
		if ($rental->getAddress()->getLocality() instanceof \Entity\Location\Location) {
			$rank['points'] += 1;
			$rank['complete'][] = 'addressLocality';
		} else {
			$rank['missing'][] = 'addressLocality';
		}

		// Address -> Address
		if (Strings::length($rental->address->address) > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'addressAddress';
		} else {
			$rank['missing'][] = 'addressAddress';
		}

		// Address -> Postal Code
		if (Strings::length($rental->address->postalCode) > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'addressPostalCode';
		} else {
			$rank['missing'][] = 'addressPostalCode';
		}

		// Contact Name
		if (Strings::length($rental->contactName) > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'contactName';
		} else {
			$rank['missing'][] = 'contactName';
		}

		// Contact Phones
		if ($rental->phone) {
			$rank['points'] += 2;
			$rank['complete'][] = 'phone';
		} else {
			$rank['missing'][] = 'phone';
		}

		// Contact Emails
		if ($rental->email) {
			$rank['points'] += 1;
			$rank['complete'][] = 'email';
		} else {
			$rank['missing'][] = 'email';
		}

		// Contact Url
		if ($rental->url) {
			$rank['points'] += 1;
			$rank['complete'][] = 'url';
		} else {
			$rank['missing'][] = 'url';
		}

		// Amenities
		$t = count($rental->amenities);
		if ($t > 0) {
			$rank['points'] += ($t > 25 ? 25 : $t);
			$rank['complete'][] = 'amenities';
		} else {
			$rank['missing'][] = 'amenities';
		}

		// Spoken Languages
		$t = count($rental->spokenLanguages);
		if ($t > 0) {
			$rank['points'] += ($t > 3 ? 3 : $t);
			$rank['complete'][] = 'spokenLanguages';
		} else {
			$rank['missing'][] = 'spokenLanguages';
		}

		// Check In
		if ($rental->checkIn) {
			$rank['points'] += 1;
			$rank['complete'][] = 'checkIn';
		} else {
			$rank['missing'][] = 'checkIn';
		}

		// Check Out
		if ($rental->checkOut) {
			$rank['points'] += 1;
			$rank['complete'][] = 'checkOut';
		} else {
			$rank['missing'][] = 'checkOut';
		}

		// Max Capacity
		if ($rental->maxCapacity > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'maxCapacity';
		} else {
			$rank['missing'][] = 'maxCapacity';
		}

		// Prices
		if ($rental->getPrice()->getSourceAmount() > 0) {
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
		if ($rental->classification !== NULL) {
			$rank['points'] += 1;
			$rank['complete'][] = 'classification';
		} else {
			$rank['missing'][] = 'classification';
		}

		//Prices - //@todo
		$t = count($rental->pricelists);
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
		$t = count($rental->pricelistRows);
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
		$t = count($rental->images)*2;
		if ($t > 0) {
			$rank['points'] += ($t > 40 ? 40 : $t);
			$rank['complete'][] = 'images';
		} else {
			$rank['missing'][] = 'images';
		}

		// Rooms
		if (Strings::length($rental->rooms) > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'rooms';
		} else {
			$rank['missing'][] = 'rooms';
		}

		// Bedroom count
		if ($rental->bedroomCount > 0) {
			$rank['points'] += 1;
			$rank['complete'][] = 'bedroomCount';
		} else {
			$rank['missing'][] = 'bedroomCount';
		}

		// Update Rental
		$rank['status'] = \Entity\Rental\Rental::STATUS_LIVE;
		foreach ($rental->missingInformation as $key => $value) {
			$rental->removeMissingInformation($value);
		}

		if(count($rank['missing'])) {
			$rentalInformationRepository = $this->em->getRepository(RENTAL_INFORMATION_ENTITY);
			$allInformationType = $rentalInformationRepository->findAll();
			$allInformationType = \Tools::arrayMap($allInformationType, function($k, $e){return $e->getSlug();}, NULL);
			foreach ($rank['missing'] as $key => $value) {
				$information = $allInformationType[$value];
				if (!$information) {
					throw new \Exception("Rental::CalculateRank - MissingInformation type does not exist: ".$value, 1);
				}
				$rental->addMissingInformation($information);
				if ((in_array($value, $conditionalCompulsoryInformation) && $pricesCompulsory) || $information->compulsory) {
					$rank['status'] = \Entity\Rental\Rental::STATUS_DRAFT;
				}
			}
		}

		$rental->status = $rank['status'];
		$rental->rank = $rank['points'];
	}

}