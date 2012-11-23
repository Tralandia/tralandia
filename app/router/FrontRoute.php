<?php


namespace Routers;

use Nette,
	Nette\Application,
	Nette\Caching,
	Nette\Utils\Strings,
	Nette\Utils\Arrays;

class FrontRoute implements Nette\Application\IRouter {
	
	protected $db;
	protected $metadata = array(
		'presenter' => 'Rental',
		'action' => 'list',
	);
	protected $hostPattern;
	protected $hostMask;
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
		'primaryLocation' => true,
		'language' => null,
		'location' => null,
		'tag' => null,
		'attractionType' => null,
		'rentalType' => null,
	);

	public $locationRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $rentalRepositoryAccessor;
	public $attractionRepositoryAccessor;
	public $routingPathSegmentRepositoryAccessor;
	public $domainRepositoryAccessor;

	
	public function __construct(Caching\Cache $cache, $hostMask)
	{
		//$this->metadata = $metadata;
		$this->cache = $cache;
		$this->setHostPattern($hostMask);
		$this->setHostMask($hostMask);
		$this->loadCache();
	}

	public function match(Nette\Http\IRequest $httpRequest)
	{
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

	public function setHostPattern($mask)
	{
		$patter = Strings::replace($mask, '~<\w+>~', function ($m) {
			return '(?P'.$m[0].'\w+)';
		});
		$patter = str_replace('.', '\.', $patter);
		$this->hostPattern = '~^'.$patter.'$~';
	}

	public function setHostMask($mask)
	{
		$this->hostMask =  $mask;
	}

	public function constructUrl(Nette\Application\Request $appRequest, Nette\Http\Url $refUrl)
	{
		// debug('$appRequest', $appRequest);
		// debug('$refUrl', $refUrl);
		$url = $this->getUrlByAppRequest($appRequest, $refUrl);
		return "$url";
	}

	public function getParamsByHttpRequest($httpRequest)
	{
		// debug('$httpRequest', $httpRequest);
		$params = \Nette\ArrayHash::from(array(
				'query' => \Nette\ArrayHash::from(array()),
			));
		$url = $httpRequest->url;
		
		list($languageIso, $domainName, $countryIso) = $this->parseHost($url->getHost());

		$pathSegments = array_filter(explode('/', $url->getPath()));

		// Params
		$country = $this->locationRepositoryAccessor->get()->findOneByIso($countryIso);
		if(!$country) {
			$country = $this->locationRepositoryAccessor->get()->findOneByIso($this->getMetadata('country'));
		}

		$language = $this->languageRepositoryAccessor->get()->findOneByIso($languageIso);
		if(!$language || !$language->supported) {
			$language = $country->defaultLanguage;
		}

		$params->primaryLocation = $country->id;
		$params->language = $language->id;


		if(count($pathSegments) == 0) {
			$params->presenter = $this->getMetadata('presenter');
			$params->action = $this->getMetadata('action');
		} else if(count($pathSegments) == 1) {
			$pathSegment = reset($pathSegments);
			// debug($pathSegment);
			if($match = Strings::match($pathSegment, '~\.*-a([0-9]+)~')) {
				if($attraction = $this->attractionRepositoryAccessor->get()->find($match[1])) {
					$params->attraction = $attraction;
					$params->presenter = 'Attraction';
					$params->action = 'detail';
					$params->id = $match[1];
				}
			} else if($match = Strings::match($pathSegment, '~\.*-r([0-9]+)~')) {
				if($rental = $this->rentalRepositoryAccessor->get()->find($match[1])) {
					$params->rental = $rental;
					$params->presenter = 'Rental';
					$params->action = 'detail';
					$params->id = $match[1];
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
			'params' => array(
				'action' => $params->action
			),
			'presenter' => $params->presenter,
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

		// debug('$return', $return);
		return $return;
	}

	public function getPathSegmentList($pathSegments, $params)
	{
		$pathSegmentList = $this->routingPathSegmentRepositoryAccessor->get()->findForRouter($params->language, $params->primaryLocation, $pathSegments);
		return $pathSegmentList;
	}

	public function getUrlByAppRequest($appRequest, $refUrl)
	{
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

		list($refLanguageIso, $refDomainName, $refCountryIso) = $this->parseHost($refUrl->getHost());

		$country = $this->locationRepositoryAccessor->get()->find($params['primaryLocation']);
		$language = $this->languageRepositoryAccessor->get()->find($params['language']);

		$segments = array();
		if($presenter == 'Rental' && $action == 'detail') {
			$rental = $this->rentalRepositoryAccessor->get()->find($params['id']);
			$segments[] = $rental->slug . '-r' . $rental->id;
		} else {
			foreach (static::$pathSegmentTypes as $key => $value) {
				if(!isset($params[$key])) continue;
				$segment = $this->getSegmentById($key, $params);
				if(!$segment) continue;
				$segments[$value] = $segment;
			}
			ksort($segments);
		}


		$url = clone $refUrl;
		$host = $this->buildHost(($language->id == $country->defaultLanguage->id ? 'www' : $language->iso), $refDomainName, $country->iso);
		$url->setHost($host);
		$path = '/' . implode('/', $segments);
		$url->setPath($path);
		// debug('url', "$url");
		return $url;
	}

	public function getSegmentById($segmentName, $params)
	{
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
				$segmentRow = $this->routingPathSegmentRepositoryAccessor->get()->findOneBy(array(
					'type' => static::$pathSegmentTypes[$segmentName], 
					'entityId' => $segmentId
				));
			} else {
				$segmentRow = $this->routingPathSegmentRepositoryAccessor->get()->findOneBy(array(
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

	public function getMetadata($key)
	{
		return $this->metadata[$key];
/*		if($key == 'language') {
			return $this->languageRepositoryAccessor->get()->findOneByIso($this->metadata[$key]);
		} else if($key == 'country') {
			return $this->locationRepositoryAccessor->get()->findOneByIso($this->metadata[$key]);
		} else {
			return $this->metadata[$key];
		}
*/	}

	public function checkDomain($domain)
	{
		$exists = false;
		if(is_array(static::$cached['domain'])) {
			$domainsFlip = array_flip(static::$cached['domain']);
			if(array_key_exists($domain, $domainsFlip)) {
				$exists = true;
			}
		} else {
			$exists = (bool) $this->domainRepositoryAccessor->get()->findOneByDomain($domain);
		}
		return $exists;
	}

	protected function loadCache()
	{
		static::$cached = array();
		static::$cached['domain'] = $this->cache->load('domain');
		foreach (static::$pathSegmentTypes as $key => $value) {
			static::$cached[$key] = $this->cache->load($key);
		}
	}

	public static function getPathSegmentTypes ()
	{
		return static::$pathSegmentTypes;
	}

	public function buildHost($language, $domain, $country)
	{
		return str_replace(
			array('<language>', '<domain>', '<country>'), 
			array($language, $domain, $country), 
			$this->hostMask
		);
	}

	public function parseHost($host)
	{
		$match = Strings::match($host, $this->hostPattern);
		if(!is_array($match)) {
			throw new \Nette\InvalidArgumentException('Wrong router.hostPattern');
		}
		return array(
			Arrays::get($match, 'langauge', NULL),
			Arrays::get($match, 'domain', NULL),
			Arrays::get($match, 'country', NULL),
		);
	}

}