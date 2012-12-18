<?php

namespace Service\Rental;

use Service, Doctrine, Entity;
use Model\Medium\IMediumDecoratorFactory;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;
/**
 * @author Dávid Ďurika
 */
class RentalService extends Service\BaseService 
{
	protected $rentalSearchCachingFactory;
	protected $rentalRepositoryAccessor;
	protected $rentalInformationRepositoryAccessor;
	protected $phraseDecoratorFactory;


	public function inject(\Extras\Cache\IRentalSearchCachingFactory $rentalSearchCachingFactory, \Model\Phrase\IPhraseDecoratorFactory $phraseDecoratorFactory)
	{
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
		$this->phraseDecoratorFactory = $phraseDecoratorFactory;
	}

	public function injectRepository(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
		$this->rentalInformationRepositoryAccessor = $dic->rentalInformationRepositoryAccessor;
	}

	public function isFeatured($strict = FALSE) {
		if ($strict) {
			return (bool)$this->rentalRepositoryAccessor->get()->isFeatured($this->entity);
		} else {
			return (bool)$this->rentalSearchCachingFactory->create($this->entity->primaryLocation)->isFeatured($this->entity);
		}
	}

	public function calculateRank() {
		$r = $this->entity;

		$conditionalCompulsoryInformation = array('priceSeason', 'priceOffSeason');
		$pricesCompulsory = !$r->pricesUponRequest;

		$rank = array(
			'points' => 0,
			'missing' => array(),
			'status' => NULL,
		);

		// Primary location
		if ($r->primaryLocation) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'primaryLocation';
		}

		// Rental Type
		if ($r->type) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'type';
		}

		// Rental Name
		$nameDecorator = $this->phraseDecoratorFactory->create($r->name);
		if ($nameDecorator->getValidTranslationsCount() > 0) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'name';
		}

		// Teaser
		$teaserDecorator = $this->phraseDecoratorFactory->create($r->teaser);
		if ($teaserDecorator->getValidTranslationsCount() > 0) {
			$rank['points'] += 3;
		} else {
			$rank['missing'][] = 'teaser';
		}

		// Interview Answers
		$t = 0;
		foreach ($r->interviewAnswers as $key => $value) {
			$decorator = $this->phraseDecoratorFactory->create($value->answer);
			if ($teaserDecorator->getValidTranslationsCount() > 0) {
				$t++;
			}
		}
		if ($t > 0) {
			$rank['points'] += $t;
		} else {
			$rank['missing'][] = 'interviewAnswers';
		}

		// Address -> GPS
		if ($r->address->latitude !== NULL && $r->address->longitude !== NULL) {
			$rank['points'] += 4;
		} else {
			$rank['missing'][] = 'addressGps';
		}

		// Address -> Locality
		if ($r->address->locality instanceof \Entity\Location\Location) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'addressLocality';
		}

		// Address -> Address
		if (Strings::length($r->address->address) > 0) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'addressAddress';
		}

		// Address -> Postal Code
		if (Strings::length($r->address->postalCode) > 0) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'addressPostalCode';
		}

		// Contact Name
		if (Strings::length($r->contactName) > 0) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'contactName';
		}

		// Contact Phones
		if (count($r->phones) > 0) {
			$rank['points'] += 2;
		} else {
			$rank['missing'][] = 'phones';
		}

		// Contact Emails
		if (count($r->emails) > 0) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'emails';
		}

		// Contact Urls
		if (count($r->urls) > 0) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'urls';
		}

		// Amenities
		$t = count($r->amenities);
		if ($t > 0) {
			$rank['points'] += ($t > 25 ? 25 : $t);
		} else {
			$rank['missing'][] = 'amenities';
		}

		// Tags
		$t = count($r->tags);
		if ($t > 0) {
			$rank['points'] += ($t > 5 ? 5 : $t);
		} else {
			$rank['missing'][] = 'tags';
		}

		// Spoken Languages
		$t = count($r->spokenLanguages);
		if ($t > 0) {
			$rank['points'] += ($t > 3 ? 3 : $t);
		} else {
			$rank['missing'][] = 'spokenLanguages';
		}

		// Check In
		if ($r->checkIn) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'checkIn';
		}

		// Check Out
		if ($r->checkOut) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'checkOut';
		}

		// Max Capacity
		if ($r->maxCapacity > 0) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'maxCapacity';
		}

		// Prices Season
		if ($r->priceSeason > 0) {
			$rank['points'] += 3;
		} else {
			if ($pricesCompulsory) {
				$rank['missing'][] = 'priceSeason';
			} else {
				$rank['missing'][] = 'priceSeason';
			}
		}

		// Prices Off Season
		if ($r->priceOffSeason > 0) {
			$rank['points'] += 3;
		} else {
			if ($pricesCompulsory) {
				$rank['missing'][] = 'priceOffSeason';
			} else {
				$rank['missing'][] = 'priceOffSeason';
			}
		}

		// Classification
		if ($r->classification !== NULL) {
			$rank['points'] += 1;
		} else {
			$rank['missing'][] = 'classification';
		}

		// Prices - //@todo
		// $t = count($r->priceLists);
		// if ($t > 0) {
		// 	$rank['points'] += ($t > 35 ? 35 : $t);
		// } else {
		// 	if ($pricesCompulsory) {
		// 		$rank['missing'][] = 'priceLists';
		// 	} else {
		// 		$rank['missing'][] = 'priceLists';
		// 	}
		// }

		// Images
		$t = count($r->images)*2;
		if ($t > 0) {
			$rank['points'] += ($t > 40 ? 40 : $t);
		} else {
			$rank['missing'][] = 'images';
		}

		// Update Rental
		$rank['status'] = \Entity\Rental\Rental::STATUS_LIVE;
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