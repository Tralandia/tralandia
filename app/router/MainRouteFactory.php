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
	protected $hostPattern;

	public $locationRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $rentalRepositoryAccessor;
	public $attractionRepositoryAccessor;
	public $routingPathSegmentRepositoryAccessor;
	public $domainRepositoryAccessor;

	public function __construct(Caching\Cache $cache, $hostPattern) {
		list($this->cache, $this->hostPattern) = func_get_args();
	}

	public function create() {
		$route = new MainRoute($this->cache, $this->hostPattern);
		$route->locationRepositoryAccessor = $this->locationRepositoryAccessor;
		$route->languageRepositoryAccessor = $this->languageRepositoryAccessor;
		$route->rentalRepositoryAccessor = $this->rentalRepositoryAccessor;
		$route->attractionRepositoryAccessor = $this->attractionRepositoryAccessor;
		$route->routingPathSegmentRepositoryAccessor = $this->routingPathSegmentRepositoryAccessor;
		$route->domainRepositoryAccessor = $this->domainRepositoryAccessor;
		return $route;
	}

}