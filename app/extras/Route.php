<?php


namespace Extras;

use Nette,
	Nette\Application,
	Nette\Caching,
	Nette\Utils\Strings;

class Route implements Nette\Application\IRouter {
	
	protected $db;
	protected $metadata;
	protected $cache;
	protected $cachedUrls;
	protected $cachedDomains;
	protected $queryParams = array(
			'lfPeople' => array(),
			'lfFood' => array(),
			'lfDog' => array(),
		);

	protected static $pathSegmentTypes = array(
			'page' => 2,
			'attractionType' => 4,
			'location' => 6,
			'rentalType' => 8,
			'tag' => 10,
		);

	protected $appParams = array(
		'id' => null,
		'country' => true,
		'language' => null,
		'location' => null,
		'tag' => null,
		'type' => null,
	);

	
	public function __construct(Caching\IStorage $cacheStorage, array $metadata) {
		$this->metadata = $metadata;

		$this->cache = new Caching\Cache($cacheStorage, 'Router');
		$this->loadCache();

	}


	public function match(Nette\Http\IRequest $httpRequest) {
		if(!$this->checkDomain($httpRequest->url->getHost())) {
			return NULL;
		}

		$params = $this->getParamsByHttpRequest($httpRequest);

		$presenter = $params['presenter'];
		$params = $params['params'];
		return new Application\Request(
			$presenter,
			$httpRequest->getMethod(),
			$params,
			$httpRequest->getPost(),
			$httpRequest->getFiles(),
			array(Application\Request::SECURED => $httpRequest->isSecured())
		);
	}

	public function constructUrl(Nette\Application\Request $appRequest, Nette\Http\Url $refUrl) {
		debug('$appRequest', $appRequest);
		debug('$refUrl', $refUrl);
		$url = $this->getUrlByAppRequest($appRequest);
		return 'http://www.tra.sk/mala-fatra/chalupy/prazdninovy-pobyt?lfPeople=6&lfFood=6';
	}

	public function getParamsByHttpRequest($httpRequest) {
		$params = \Nette\ArrayHash::from(array(
				'query' => \Nette\ArrayHash::from(array()),
			));
		$url = $httpRequest->url;
		debug('$httpRequest', $httpRequest);
		list($languageIso, $domainName, $countryIso) = explode('.', $url->getHost(), 3);
		$pathSegments = array_filter(explode('/', $url->getPath()));


		// Params
		$country = \Service\Location\Location::getByIso($countryIso);
		if(!$country) {
			$country = \Service\Location\Location::getByIso($this->getMetadata('country'));
		}

		$language = \Service\Dictionary\Language::getByIso($languageIso);
		if(!$language || !$language->supported) {
			$language = \Service\Dictionary\Language::get($country->defaultLanguage);
		}
		$params->country = $country->id;
		$params->language = $language->id;


		if(count($pathSegments) == 0) {
			$params->presenter = $this->getMetadata('presenter');
			$params->action = $this->getMetadata('action');
		} else if(count($pathSegments) == 1) {
			$pathSegment = reset($pathSegments);
			debug($pathSegment);
			if($match = preg_match('~\.*-a([0-9]+)~', $pathSegment)) {
				if($attraction = \Service\Attraction\Attraction::get($match[1])) {
					$params->attraction = $attraction;
					$params->presenter = 'Attraction';
					$params->action = 'show';
				}
			} else if($match = Strings::match($pathSegment, '~\.*-r([0-9]+)~')) {
				if($rental = \Service\Rental\Rental::get($match[1])) {
					$params->rental = $rental;
					$params->presenter = 'Rental';
					$params->action = 'show';
				}
			}
		}

		if(!isset($params->presenter)) {
			$params->presenter = $this->getMetadata('presenter');
		}

		if(!isset($params->action)) {
			$params->action = $this->getMetadata('action');
		}

		// Query
		if(count($httpRequest->query)) {
			foreach ($httpRequest->query as $key => $value) {
				if(!array_key_exists($key, $this->queryParams)) continue;
				$params->query->{$key} = $value;
			}
		}

		$segmentList = $this->getPathSegmentList($pathSegments, $params);
		$pathSegmentTypesFlip = array_flip($this->pathSegmentTypes);
		foreach ($segmentList as $key => $value) {
			$params->{$pathSegmentTypesFlip[$value->type]} = $value->pathSegment;
		}
		if($segmentList->count() != count($pathSegments)) {
			// @todo pocet najdenych pathsegmentov je mensi
			// ak nejake chybaju tak ich skus najst v PathSegmentsOld
		}


		$return = array(
			'params' => array(),
			'presenter' => $params->presenter,
			'action' => $params->action,
		);
		foreach ($this->appParams as $key => $value) {
			if($value === true && !isset($params->$key)) {
				$params->$key = $this->getMetadata($key);
			} 

			if(isset($params->$key)) {

				$return['params'][$key] = $params->$key;
			}
		}
		$return['params'] = array_merge($return['params'], (array) $params->query);

		debug('$return', $return);
		return $return;
	}

	public function getPathSegmentList($pathSegments, $params) {
		$criteria = array();
		if($pathSegments) $criteria['pathSegment'] = $pathSegments;
		$criteria['country'] = array($params->country, 0);
		$criteria['language'] = array($params->language, 0);

		$pathSegmentList = \Service\Routing\PathSegmentList::getBy((array) $criteria, $orderBy = array('type' => 'ASC'));
		debug('$pathSegmentList', $pathSegmentList);
		return $pathSegmentList;
	}

	public function getUrlByAppRequest($appRequest) {
		$params = $appRequest->getParameters();
		$query = array();
		foreach ($this->queryParams as $key => $value) {
			if(array_key_exists($key, $params)) {
				$query[$key] = $params[$key];
				unset($params[$key]);
			}
		}
		$presenter = $appRequest->getPresenterName();
		$action = $params['action'];
		unset($params['action']);
		debug($params, $query, $presenter, $action);
	}

	public function getMetadata($key) {
		return $this->metadata[$key];
/*		if($key == 'language') {
			return \Service\Dictionary\Language::getByIso($this->metadata[$key]);
		} else if($key == 'country') {
			return \Service\Location\Location::getByIso($this->metadata[$key]);
		} else {
			return $this->metadata[$key];
		}
*/	}

	public function checkDomain($domain) {
		if(!is_array($this->cachedDomains)) {
			$this->generateDomainCache();
		}
		if(!array_key_exists($domain, $this->cachedDomains)) {

		}
		return true;
	}


	protected function loadCache() {
		$this->cachedUrls = $this->cache->load('urls');
		$this->cachedDomains = $this->cache->load('domains');
	}

	protected function generateDomainCache() {
		$this->cache->save('domains', array());
		$this->cachedDomains = $this->cache->load('domains');
	}

	public static function getPathSegmentTypes () {
		return static::$pathSegmentTypes;
	}

}