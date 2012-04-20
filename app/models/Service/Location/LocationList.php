<?php

namespace Service\Location;

use Extras\Models\ServiceList;

class LocationList extends ServiceList {

	const MAIN_ENTITY_NAME = '\Entity\Location\Location';

	public function getRentalsCount() {

		$location = self::get($id);

	}

}