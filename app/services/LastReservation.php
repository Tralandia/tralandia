<?php

class LastReservation {


	/**
	 * @var Nette\Http\SessionSection
	 */
	protected $section;


	/**
	 * @param \Nette\Http\Session $session
	 */
	public function __construct(\Nette\Http\Session $session)
	{
		$section = $session->getSection('lastReservation');
		$section->setExpiration(NULL);

		$this->section = $section;
	}

	public function setData(array $data)
	{
		$this->section->data = $data;
	}


	public function getData()
	{
		return $this->section->data;
	}

}
