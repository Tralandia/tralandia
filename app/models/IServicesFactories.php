<?php

namespace Service\Contact;

interface IAddressNormalizerFactory {
	/** @return \Service\Contact\AddressNormalizer */
	function create(\Entity\Contact\Address $address);
}

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

interface IUpdateRentalSearchCacheRobotFactory {
	function create(\Entity\Location\Location $location);
}