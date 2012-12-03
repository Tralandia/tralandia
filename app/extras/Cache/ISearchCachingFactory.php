<?php
namespace Extras\Cache;

interface ISearchCachingFactory {
	
	public function create(\Entity\Location\Location $location);

}

interface IRentalSearchCachingFactory {
	
	public function create(\Entity\Rental\Rental $rental);

}

interface ISearchCacheFactory {
	
	public function create( $namespace);

}
