<?php
namespace Extras\Email\Variables;

use Nette;

/**
 * LocationVariables class
 *
 * @author Dávid Ďurika
 */
class LocationVariables extends Nette\Object {

	/**
	 * @var \Entity\Location\Location
	 */
	private $location;

	/**
	 * @param \Entity\Location\Location $location
	 */
	public function __construct(\Entity\Location\Location $location) {
		$this->location = $location;
	}

}

interface ILocationVariablesFactory {
	/**
	 * @param \Entity\Location\Location $location
	 *
	 * @return LocationVariables
	 */
	function create(\Entity\Location\Location $location);
}