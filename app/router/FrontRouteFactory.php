<?php
namespace Routers;

use Nette\Caching;
/**
 * FrontRouteFactory class
 *
 * @author Dávid Ďurika
 */
class FrontRouteFactory {

	protected $cache;
	protected $hostPattern;

	public $locationRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $rentalRepositoryAccessor;
	public $attractionRepositoryAccessor;
	public $routingPathSegmentRepositoryAccessor;
	public $domainRepositoryAccessor;
	public $pageRepositoryAccessor;

	public function __construct(Caching\Cache $cache, $hostPattern) {
		list($this->cache, $this->hostPattern) = func_get_args();
	}

	public function create() {
		$route = new FrontRoute($this->cache, $this->hostPattern);
		$route->locationRepositoryAccessor = $this->locationRepositoryAccessor;
		$route->languageRepositoryAccessor = $this->languageRepositoryAccessor;
		$route->rentalRepositoryAccessor = $this->rentalRepositoryAccessor;
		$route->attractionRepositoryAccessor = $this->attractionRepositoryAccessor;
		$route->routingPathSegmentRepositoryAccessor = $this->routingPathSegmentRepositoryAccessor;
		$route->domainRepositoryAccessor = $this->domainRepositoryAccessor;
		$route->pageRepositoryAccessor = $this->pageRepositoryAccessor;
		return $route;
	}

}