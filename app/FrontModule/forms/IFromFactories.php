<?php

namespace FrontModule\Forms\Rental {

	interface IReservationFormFactory {
		public function create(\Entity\Rental\Rental $rental);
	}
}


namespace FrontModule\Forms {

	interface IRegistrationFormFactory {
		public function create(\Entity\Location\Location $country);
	}
}

