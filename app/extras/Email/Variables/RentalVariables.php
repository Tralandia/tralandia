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

	public function __construct(\Service\Rental\Rental $rental) {
		$this->rental = $rental;
	}

}