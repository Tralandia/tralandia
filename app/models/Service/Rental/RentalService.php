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

}


interface IRentalDecoratorFactory {

	/**
	 * @param Entity\Rental\Rental $entity
	 *
	 * @return RentalService
	 */
	public function create(\Entity\Rental\Rental $entity);
}
