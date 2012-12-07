<?php

namespace Service\Rental;

interface IRentalSearchServiceFactory {
	function create(\Entity\Location\Location $location);
}

namespace Service\Seo;

interface ISeoServiceFactory {
	/** @return \Service\Seo\SeoService */
	function create($url, \Nette\Application\Request $request);
}

namespace Service\Robot;

interface IUpdateRentalSearchKeysCacheRobotFactory {
	function create(\Entity\Location\Location $location);
}