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
	 * @var \Image\RentalImagePipe
	 */
	private $imagePipe;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Image\RentalImagePipe $imagePipe
	 */
	public function __construct(\Entity\Rental\Rental $rental, \Image\RentalImagePipe $imagePipe) {
		$this->rental = $rental;
		$this->imagePipe = $imagePipe;
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

	public function getVariableMainPhoto()
	{
		return $this->imagePipe->request($this->rental->getMainImage());
	}

	public function getVariablePersonalSiteLink(EnvironmentVariables $environment)
	{
		$ps = $this->rental->personalSiteConfiguration;
		if(!$ps) return '#';

		return $ps->url;

	}

}
