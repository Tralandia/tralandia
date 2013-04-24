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

	public function renderDefault() {

		$search = $this->rentalSearchFactory->create($this->environment->primaryLocation);

		$links = $this->searchOptionGenerator->generateLocationLinks(NULL, $search);
		$this->template->links = array_chunk((array) $links, ceil(count($links)/3));
	}

}
