<?php

namespace Service\Contact {

	interface IAddressNormalizerFactory {
		/**
		 * @param \Entity\Contact\Address $address
		 *
		 * @return AddressNormalizer
		 */
		function create(\Entity\Contact\Address $address);
	}
}


namespace Service\Rental {

	interface IRentalSearchServiceFactory {
		/**
		 * @param \Entity\Location\Location $location
		 *
		 * @return RentalSearchService
		 */
		function create(\Entity\Location\Location $location);
	}
}

namespace Extras {


}
