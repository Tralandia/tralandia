<?php
namespace Extras\Email\Variables;

use Nette;

/**
 * RentalVariables class
 *
 * @author Dávid Ďurika
 */
class RentalVariables extends Nette\Object {

	private $rental;

	public function __construct(\Service\Rental\RentalService $rental) {
		$this->rental = $rental;
	}

	public function getVariableLink() {
		return 'http://www.tralandia.sk/moderne-apartmany-riviera-s-vynikajucou-polohou-na-liptove-6642';
	}

}