<?php
namespace Extras\Email\Variables;

use Nette;

/**
 * LocationVariables class
 *
 * @author Dávid Ďurika
 */
class LocationVariables extends Nette\Object {

	private $location;

	public function __construct(\Service\Location\LocationService $location) {
		$this->location = $location;
	}

}