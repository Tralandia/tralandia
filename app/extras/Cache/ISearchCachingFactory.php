<?php
namespace Extras\Cache;

interface ISearchCachingFactory {
	
	public function create(\Entity\Location\Location $location);

}
