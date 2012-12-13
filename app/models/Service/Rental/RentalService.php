<?php

namespace Service\Rental;

use Service, Doctrine, Entity;
use Model\Medium\IMediumDecoratorFactory;
use Nette\Utils\Arrays;
/**
 * @author Dávid Ďurika
 */
class RentalService extends Service\BaseService 
{
	protected $rentalSearchCachingFactory;
	protected $rentalRepositoryAccessor;

	public function inject(\Extras\Cache\IRentalSearchCachingFactory $rentalSearchCachingFactory)
	{
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
	}

	public function injectRepository(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

	public function isFeatured($strict = FALSE) {
		if ($strict) {
			return (bool)$this->rentalRepositoryAccessor->get()->isFeatured($this->entity);
		} else {
			return (bool)$this->rentalSearchCachingFactory->create($this->entity->primaryLocation)->isFeatured($this->entity);
		}
	}

}