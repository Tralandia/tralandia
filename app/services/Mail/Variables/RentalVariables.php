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
		return $environment->link('//Rental:detail', ['rental' => $this->rental]);
	}


}