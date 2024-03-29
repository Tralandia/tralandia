<?php

namespace Model\Rental;

interface IRentalDecoratorFactory {
	/** @return \Service\Rental\RentalService */
	function create(\Entity\Rental\Rental $entity);
}

interface IImageDecoratorFactory {
	/** @return \Service\Rental\ImageDecorator */
	function create(\Entity\Rental\Image $entity);
}

interface IPricelistDecoratorFactory {
	/** @return \Service\Rental\PricelistDecorator */
	function create(\Entity\Rental\Pricelist $entity);
}


namespace Model\Medium;

interface IMediumDecoratorFactory {
	/** @return \Service\Medium\MediumService */
	function create(\Entity\Medium\Medium $entity);
}


namespace Model\Phrase;

interface IPhraseDecoratorFactory {
	/** @return \Service\Phrase\PhraseService */
	function create(\Entity\Phrase\Phrase $entity);
}


namespace Model\Location;

interface ILocationDecoratorFactory {
	/** @return \Service\Location\LocationService */
	function create(\Entity\Location\Location $entity);
}

