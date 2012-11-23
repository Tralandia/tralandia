<?php

namespace Service\Rental;

interface IRentalSearchServiceFactory {
	function create(\Entity\Location\Location $location);
}
