<?php

class LastSearch {

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

	/**
	 * @param string $url
	 */
	public function setUrl($url)
	{
		$this->section->url = $url;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->section->url;
	}

	/**
	 * @param string $heading
	 */
	public function setHeading($heading)
	{
		$this->section->heading = $heading;
	}

	/**
	 * @return string
	 */
	public function getHeading()
	{
		return $this->section->heading;
	}

}