<?php

namespace Service\Rental;

interface IRentalSearchServiceFactory {
	function create(\Entity\Location\Location $location);
}

namespace Service\Seo;

interface ISeoServiceFactory {
	function create($url);
}

namespace Service\Robot;

interface IUpdateRentalSearchKeysCacheRobotFactory {
	function create(\Entity\Location\Location $location);
}