<?php


namespace Routers;

use Nette;
use Nette\Application;
use Nette\Caching;
use Nette\Utils\Strings;
use Nette\Utils\Arrays;

use Entity;

class FrontRoute implements Nette\Application\IRouter {

	const DEVELOPMENT = 'development';
	const PRODUCTION = 'production';
	const DEV_HOST_PATTERN = '~^(?P<language>\w+)\.(?P<country>\w+)\.(?P<domain>\w+)\.com$~';
	
	protected $metadata = array(
		'presenter' => 'Rental',
		'action' => 'list',
	);
	protected $mode;
	protected $cache;
	protected static $cached;
	protected $queryParams = array(
		'lfPeople' => array(),
		'lfFood' => array(),
		'lfDog' => array(),
		'paginator-page' => array(),
	);

	protected static $pathSegmentTypes = array(
		'page' => 2,
		'attractionType' => 4,
		'location' => 6,
		'rentalType' => 8,
		'rentalTag' => 10,
	);

	protected static $pathSegmentTypesById = array(
		2 => 'page',
		4 => 'attractionType',
		6 => 'location',
		8 => 'rentalType',
		10 => 'rentalTag',
	);

	protected $appParams = array(
		'id' => null,
		'primaryLocation' => true,
		'language' => null,
		'location' => null,
		'rental' => null,
		'rentalTag' => null,
		'attractionType' => null,
		'rentalType' => null,
		'page' => null,
	);

	public $locationRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $rentalRepositoryAccessor;
	public $rentalTypeRepositoryAccessor;
	public $attractionRepositoryAccessor;
	public $attractionTypeRepositoryAccessor;
	public $routingPathSegmentRepositoryAccessor;
	public $domainRepositoryAccessor;
	public $rentalTagRepositoryAccessor;
	public $rentalAmenityRepositoryAccessor;
	public $phraseDecoratorFactory;
	
	public function __construct(Caching\Cache $cache, $mode)
	{
		//$this->metadata = $metadata;
		$this->cache = $cache;
		$this->mode = $mode;
		$this->loadCache();
	}

	public function match(Nette\Http\IRequest $httpRequest)
	{
		// d('httpRequest', $httpRequest);
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

	public function constructUrl(Nette\Application\Request $appRequest, Nette\Http\Url $refUrl)
	{
		// d('$appRequest', $appRequest);
		// d('$refUrl', $refUrl);
		$url = $this->getUrlByAppRequest($appRequest, $refUrl);
		return "$url";
	}

	public function getParamsByHttpRequest($httpRequest)
	{
		//d('$httpRequest', $httpRequest);
		$params = \Nette\ArrayHash::from(array(
				'query' => \Nette\ArrayHash::from(array()),
			));
		$url = $httpRequest->url;
		
		list($languageIso, $domainName, $countryIso) = $this->parseHost($url->getHost());

		$pathSegments = array_filter(explode('/', $url->getPath()));

		// Params
		$country = $this->locationRepositoryAccessor->get()->findOneByIso($countryIso);
		if(!$country) {
			$domain = $this->domainRepositoryAccessor->get()->findOneByDomain('tralandia.'.$countryIso);
			if($domain) {
				$country = $this->locationRepositoryAccessor->get()->findOneByDomain($domain);
			}
		}

		if(!$country) {
			throw new \Nette\InvalidArgumentException('Country not found');
		}

		$language = $this->languageRepositoryAccessor->get()->findOneByIso($languageIso);
		if(!$language || !$language->supported) {
			$language = $country->defaultLanguage;
		}

		$params->primaryLocation = $country;
		$params->language = $language;


		if(count($pathSegments) == 0) {
			$params->presenter = 'Home';
			$params->action = 'default';
		} else if(count($pathSegments) == 1) {
			$pathSegment = reset($pathSegments);
			// debug($pathSegment);
			if($match = Strings::match($pathSegment, '~\.*-a([0-9]+)~')) {
				if($attraction = $this->attractionRepositoryAccessor->get()->find($match[1])) {
					$params->attraction = $attraction;
					$params->presenter = 'Attraction';
					$params->action = 'detail';
				}
			} else if($match = Strings::match($pathSegment, '~\.*-r([0-9]+)~')) {
				if($rental = $this->rentalRepositoryAccessor->get()->find($match[1])) {
					$params->rental = $rental;
					$params->presenter = 'Rental';
					$params->action = 'detail';
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
			foreach ($segmentList as $key => $value) {
				$params->{$key} = $value;
				// if($value->type == static::$pathSegmentTypes['attractionType']) {
				// 	$params->presenter = 'Attraction';
				// } else if($value->type == static::$pathSegmentTypes['rentalType']) {
				// 	$params->presenter = 'Rental';
				// }
			}
		} else {
			$segmentList = array();
		}
			
		if(count($segmentList) != count($pathSegments)) {
			// @todo pocet najdenych pathsegmentov je mensi
			// ak nejake chybaju tak ich skus najst v PathSegmentsOld
		}
		// if (!isset($params->page)) {
		// 	$destination = ':Front:'.$params->presenter . ':' . $params->action;
		// 	if ($destination == ':Front:Rental:list') {
		// 		$hash = array();
		// 		foreach (self::$pathSegmentTypesById as $key => $value) {
		// 			if ($key == 2) continue;
		// 			//@todo - dorobit tagAfter alebo tagBefore (ak je to tag)
		// 			if (isset($params->{$value})) {
		// 				if ($value == 'rentalTag') {
		// 					$tagName = $this->phraseDecoratorFactory->create($params->rentalTag->name);
		// 					$tagTranslation = $tagName->getTranslation($params->language);
		// 					if ($tagTranslation->position == \Entity\Phrase\Translation::BEFORE) {
		// 						$value = \Entity\Phrase\Translation::BEFORE;
		// 					} else {
		// 						$value = \Entity\Phrase\Translation::AFTER;
		// 					}
		// 					$value = 'tag'.$value;
		// 				}
		// 				$hash[] = '/'.$value;
		// 			}
		// 		}
		// 		$hash = implode('', $hash);
		// 	} else {
		// 		$hash = '';
		// 	}
		// 	$params->page = $this->pageRepositoryAccessor->get()->findOneBy(array('hash' => $hash, 'destination' => $destination));
		// }
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
		$pathSegmentListNew = array();
		
		$pathSegmentTypesFlip = array_flip(static::$pathSegmentTypes);

		$pathSegmentList = $this->routingPathSegmentRepositoryAccessor->get()->findForRouter($params->language, $params->primaryLocation, $pathSegments);
		foreach ($pathSegmentList as $key => $value) {
			$t = self::$pathSegmentTypesById[$value->type];
			$keyTemp = $pathSegmentTypesFlip[$value->type];
			$accessor = $t.'RepositoryAccessor';
			$pathSegmentListNew[$keyTemp] = $this->{$accessor}->get()->find($value->entityId);
		}
		return $pathSegmentListNew;
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
		
		//d($params, $query, $presenter, $action);

		$country = $params['primaryLocation'];
		if(!$country instanceof Entity\Location\Location) {
			throw new \Nette\InvalidArgumentException('Parameter "primaryLocation" must be instance of Entity\Location\Location');
		}

		$language = $params['language'];
		if(!$language instanceof Entity\Language) {
			throw new \Nette\InvalidArgumentException('Parameter "language" must be instance of Entity\Language');
		}

		$segments = array();
		if($presenter == 'Rental' && $action == 'detail') {
			$rental = $params['rental'];
			if(!$rental instanceof Entity\Rental\Rental) {
				throw new \Nette\InvalidArgumentException('Parameter "rental" must be instance of Entity\Rental\Rental');
			}
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

		$host = $this->buildHost($language, $country, $refUrl);
		
		$url = clone $refUrl;
		$url->setHost($host);
		$path = '/' . implode('/', $segments);
		$url->setPath($path);
		$url->setQuery($query);
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

	public function buildHost($language, $country, $refUrl)
	{
		$languageIso = $country->defaultLanguage && $language->id == $country->defaultLanguage->id ? 'www' : $language->iso;

		if($this->mode == self::DEVELOPMENT)
			return $languageIso . '.' . $country->iso . '.' . 'tra.com';

		if($this->mode == self::PRODUCTION)
			if($country->domain) 
				return $languageIso . '.' . $country->domain->domain;
			else 
				return $languageIso . '.tralandia.' . $country->iso;
	}

	public function parseHost($host)
	{
		if($this->mode == self::DEVELOPMENT)
			if(!$match = Strings::match($host, self::DEV_HOST_PATTERN))
				throw new \Nette\InvalidArgumentException("Host '$host' do not match with pattern ".self::DEV_HOST_PATTERN);

		# @todo
		// if($this->mode == self::PRODUCTION)

		return array(
			Arrays::get($match, 'langauge', NULL),
			Arrays::get($match, 'domain', NULL),
			Arrays::get($match, 'country', NULL),
		);
	}

}