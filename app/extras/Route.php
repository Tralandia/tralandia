<?php


namespace Extras;

use Nette,
	Nette\Application,
	Nette\Caching,
	Nette\Utils\Strings,
	Nette\Utils\Arrays;

class Route implements Nette\Application\IRouter {
	
	protected $db;
	protected $metadata;
	protected $cache;
	protected static $cached;
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
		'attractionType' => null,
		'rentalType' => null,
	);

	
	public function __construct(Caching\Cache $cache, array $metadata) {
		$this->metadata = $metadata;

		$this->cache = $cache;
		$this->loadCache();
	}


	public function match(Nette\Http\IRequest $httpRequest) {
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
		$url = $this->getUrlByAppRequest($appRequest, $refUrl);
		return $url;
	}

	public function getParamsByHttpRequest($httpRequest) {
		$params = \Nette\ArrayHash::from(array(
				'query' => \Nette\ArrayHash::from(array()),
			));
		$url = $httpRequest->url;
		debug('$httpRequest', $httpRequest);
		list($languageIso, $domainName, $countryIso) = explode('.', $url->getHost(), 3);

		if($domainName !== 'tra' && !$this->checkDomain($url->getHost())) {
			$countryIso = $this->getMetadata('country');
		}


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

		if(count($pathSegments)) {
			$segmentList = $this->getPathSegmentList($pathSegments, $params);
			$pathSegmentTypesFlip = array_flip(static::$pathSegmentTypes);
			foreach ($segmentList as $key => $value) {
				$params->{$pathSegmentTypesFlip[$value->type]} = $value->entityId;
				if($value->type == static::$pathSegmentTypes['attractionType']) {
					$params->presenter = 'Attraction';
				} else if($value->type == static::$pathSegmentTypes['rentalType']) {
					$params->presenter = 'Rental';
				}
			}
		} else {
			$segmentList = array();
		}
			
		if(count($segmentList) != count($pathSegments)) {
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
		$criteria['pathSegment'] = $pathSegments;
		$criteria['country'] = array($params->country, 0);
		$criteria['language'] = array($params->language, 0);

		$pathSegmentList = \Service\Routing\PathSegmentList::getBy((array) $criteria, $orderBy = array('type' => 'ASC'));
		debug('$pathSegmentList', $pathSegmentList);
		return $pathSegmentList;
	}

	public function getUrlByAppRequest($appRequest, $refUrl) {
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
		
		//debug($params, $query, $presenter, $action);

		list($refLanguageIso, $refDomainName, $refCountryIso) = explode('.', $refUrl->getHost(), 3);

		$country = \Service\Location\Location::get($params['country']);
		$language = \Service\Dictionary\Language::get($params['language']);

		$segments = array();
		foreach (static::$pathSegmentTypes as $key => $value) {
			if(!isset($params[$key])) continue;
			$segment = $this->getSegmentById($key, $params);
			if(!$segment) continue;
			$segments[$value] = $segment;
		}
		ksort($segments);

		$url = clone $refUrl;
		$host = ($language->id == $country->defaultLanguage->id ? 'www' : $language->iso) . '.' . $refDomainName . '.' . $country->iso;
		$url->setHost($host);
		$path = '/' . implode('/', $segments);
		$url->setPath($path);
		debug('url', "$url");
		return $url;
	}

	public function getSegmentById($segmentName, $params) {
		$segmentId = $params[$segmentName];
		$language = $params['language'];
		$segment = NULL;
		if(is_array(static::$cached[$segmentName])) {
			if($segmentName == 'location') {
				$segment = Arrays::get(static::$cached[$segmentName], array($segmentId), NULL);
			} else {
				$segment = Arrays::get(static::$cached[$segmentName], array($language, $segmentId), NULL);
			}
		} else {
			if($segmentName == 'location') {
			$segmentRow = \Service\Routing\PathSegment::getBy(array(
					'type' => static::$pathSegmentTypes[$segmentName], 
					'entityId' => $segmentId
				));
			} else {
				$segmentRow = \Service\Routing\PathSegment::getBy(array(
						'type' => static::$pathSegmentTypes[$segmentName], 
						'entityId' => $segmentId, 
						'language' => $language
					));
			}
			if($segmentRow) {
				$segment = $segmentRow->pathSegment;
			}
		}
		return $segment;
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
		$exists = false;
		if(is_array(static::$cached['domain'])) {
			$domainsFlip = array_flip(static::$cached['domain']);
			if(array_key_exists($domain, $domainsFlip)) {
				$exists = true;
			}
		} else {
			$exists = (bool) \Service\Domain::getByDomain($domain);
		}
		return $exists;
	}

	protected function loadCache() {
		static::$cached = array();
		static::$cached['domain'] = $this->cache->load('domain');
		foreach (static::$pathSegmentTypes as $key => $value) {
			static::$cached[$key] = $this->cache->load($key);
		}
	}


	public static function getPathSegmentTypes () {
		return static::$pathSegmentTypes;
	}

}