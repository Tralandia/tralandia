<?php

namespace FrontModule;

use SearchGenerator\OptionGenerator;

class DestinationPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Service\Rental\IRentalSearchServiceFactory
	 */
	protected $rentalSearchFactory;

	/**
	 * @autowire
	 * @var \SearchGenerator\OptionGenerator
	 */
	protected $searchOptionGenerator;

	public function __construct()
	{

	}

	public function renderDefault()
	{
		$this->template->getLinks = $this->getLocationsLinks;
	}


	public function getLocationsLinks()
	{
		$search = $this->rentalSearchFactory->create($this->environment->primaryLocation);

		$links = $this->searchOptionGenerator->generateLocationLinks(NULL, $search);
		return array_chunk((array) $links, ceil(count($links)/3));
	}

}
