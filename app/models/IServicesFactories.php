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


namespace Service\Seo {

	interface ISeoServiceFactory {
		/**
		 * @param $url
		 * @param \Nette\Application\Request $request
		 *
		 * @return SeoService
		 */
		function create($url, \Nette\Application\Request $request);
	}
}


namespace Service\Robot {

	interface IUpdateRentalSearchCacheRobotFactory {
		/**
		 * @param \Entity\Location\Location $location
		 *
		 * @return UpdateRentalSearchCacheRobot
		 */
		function create(\Entity\Location\Location $location);
	}
}

namespace Extras {

	interface ITranslatorFactory {
		/**
		 * @param \Entity\Language $language
		 *
		 * @return Translator
		 */
		function create(\Entity\Language $language);
	}
}
