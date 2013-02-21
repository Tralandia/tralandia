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
	 * @return string
	 */
	public function getVariableLink() {
		return $this->getLink('//Rental:detail', ['rental' => $this->rental]);
	}


	/**
	 * @param string $destination
	 * @param array $arguments
	 *
	 * @return string
	 */
	protected function getLink($destination, array $arguments = NULL)
	{
		return $this->application->getPresenter()->link($destination, $arguments);
	}


}