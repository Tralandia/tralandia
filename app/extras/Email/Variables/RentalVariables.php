<?php
namespace Extras\Email\Variables;

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

	public function getVariableLink() {
		return 'http://www.tralandia.sk/moderne-apartmany-riviera-s-vynikajucou-polohou-na-liptove-6642';
	}

}


interface IRentalVariablesFactory {
	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return RentalVariables
	 */
	function create(\Entity\Rental\Rental $rental);
}