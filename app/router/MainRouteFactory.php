<?php
namespace Routers;

use Nette\Caching;
/**
 * MainRouteFactory class
 *
 * @author Dávid Ďurika
 */
class MainRouteFactory {

	protected $cache;

	public $locationRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $rentalRepositoryAccessor;
	public $attractionRepositoryAccessor;
	public $routingPathSegmentRepositoryAccessor;
	public $domainRepositoryAccessor;

	public function __construct(Caching\Cache $cache) {
		list($this->cache) = func_get_args();
	}

	public function create() {
		$route = new MainRoute($this->cache);
		$route->locationRepositoryAccessor = $this->locationRepositoryAccessor;
		$route->languageRepositoryAccessor = $this->languageRepositoryAccessor;
		$route->rentalRepositoryAccessor = $this->rentalRepositoryAccessor;
		$route->attractionRepositoryAccessor = $this->attractionRepositoryAccessor;
		$route->routingPathSegmentRepositoryAccessor = $this->routingPathSegmentRepositoryAccessor;
		$route->domainRepositoryAccessor = $this->domainRepositoryAccessor;
		return $route;
	}

}