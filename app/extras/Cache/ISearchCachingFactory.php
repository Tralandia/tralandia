<?php
namespace Extras\Cache;

interface ISearchCachingFactory {

	public function create(\Entity\Location\Location $location);

}

interface IRentalSearchKeysCachingFactory {
	public function create(\Entity\Rental\Rental $rental);
}

interface ISearchCacheFactory {

	public function create( $namespace);

}
