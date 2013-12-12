<?php
namespace Mail\Variables;

use Nette;

/**
 * RentalVariables class
 *
 * @author Dávid Ďurika
 */
class RentalVariables extends Nette\Object {

	/**
	 * @var \Entity\Rental\Rental
	 */
	private $rental;

	/**
	 * @param \Entity\Rental\Rental $rental
	 */
	public function __construct(\Entity\Rental\Rental $rental) {
		$this->rental = $rental;
	}

	/**
	 * @param EnvironmentVariables $environment
	 *
	 * @return string
	 */
	public function getVariableLink(EnvironmentVariables $environment) {
		$r = $environment->link('//:Front:Rental:detail', ['rental' => $this->rental]);
		return $r;
	}

	public function getVariableEmail() {
		return $this->rental->getEmail();
	}

	public function getVariableName() {
		return $this->rental->getName();
	}

	public function getVariablePrice()
	{
		return (string) $this->rental->getPrice();
	}

}
