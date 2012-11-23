<?php

namespace Model\Rental;

interface IRentalDecoratorFactory {
	function create(\Entity\Rental\Rental $entity);
}

namespace Model\Medium;

interface IMediumDecoratorFactory {
	function create(\Entity\Medium\Medium $entity);
}

namespace Model\Task;

interface ITaskDecoratorFactory {
	function create(\Entity\Task\Task $entity);
}

namespace Model\Phrase;

interface IPhraseDecoratorFactory {
	function create(\Entity\Phrase\Phrase $entity);
}

namespace Model\Location;

interface ILocationDecoratorFactory {
	/** return Service\Location\LocationService */
	function create(\Entity\Location\Location $entity);
}

