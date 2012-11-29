<?php

namespace Service\Rental;

interface IRentalSearchServiceFactory {
	function create(\Entity\Location\Location $location);
}

namespace Service\Seo;

interface ISeoServiceFactory {
	function create(\Nette\Application\Request $request);
}