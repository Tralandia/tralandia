<?php

class LastSeen {

	/**
	 * @var Nette\Http\SessionSection
	 */
	protected $section;


	public function __construct(\Nette\Http\SessionSection $section)
	{
		$this->section = $section;
	}

	/**
	 * @return bool
	 */
	public function exists()
	{
		return is_array($this->getRentals());
	}

	/**
	 * @param array $rentals
	 */
	public function setRentals(array $rentals)
	{
		$this->section->rentals = $rentals;
	}

	/**
	 * @return mixed|null
	 */
	public function getRentals()
	{
		if(isset($this->section->rentals)) {
			$rentals = $this->section->rentals;

			return count($rentals) ? $rentals : NULL;
		}

		return NULL;
	}

}
