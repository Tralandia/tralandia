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

	protected $pathSegmentTypes = array(
			'page' => 2,
			'attractionType' => 4,
			'location' => 6,
			'rentalType' => 8,
			'tag' => 10,
		);
	
	public function __construct(Caching\IStorage $cacheStorage, $metadata) {
		if (is_string($metadata)) {
			$a = strrpos($metadata, ':');
			if (!$a) {
				throw new Nette\InvalidArgumentException("Second argument must be array or string in format Presenter:action, '$metadata' given.");
			}
			$metadata = array(
				'presenter' => substr($metadata, 0, $a),
				'action' => $a === strlen($metadata) - 1 ? Application\UI\Presenter::DEFAULT_ACTION : substr($metadata, $a + 1),
			);
		}
		$this->metadata = $metadata;

		$this->cache = new Caching\Cache($cacheStorage, 'Router');
		$this->loadCache();

	}


	public function match(Nette\Http\IRequest $httpRequest) {
		if(!$this->checkDomain($httpRequest->url->getHost())) {
			return NULL;
		}

		$params = $this->getParamsByHttpRequest($httpRequest);

		$presenter = $this->metadata['presenter'];
		$params = array();
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
		return NULL;
	}

	public function getParamsByHttpRequest($httpRequest) {
		$params = \Nette\ArrayHash::from(array(
				'query' => \Nette\ArrayHash::from(array()),
			));
		$url = $httpRequest->url;
		debug($httpRequest);
		list($languageIso, $domainName, $countryIso) = explode('.', $url->getHost(), 3);
		$pathSegments = array_filter(explode('/', $url->getPath()));

		$country = \Service\Location\Country::getByIso($countryIso);
		if($languageIso === 'www') {
			$language = \Service\Dictionary\Language::get($country->defaultLanguage);
		} else {
			$language = \Service\Dictionary\Language::getByIso($languageIso);
		}
		if(!$language->supported) {
			// @todo spravit redirect na defaultny TRA jazyk
		}
		$params->country = $country;
		$params->language = $language;


		if(count($pathSegments) == 0) {
			$params->page = 'home';
		} else if(count($pathSegments) == 1) {
			$pathSegment = reset($pathSegments);
			debug($pathSegment);
			if($match = preg_match('~\.*-a([0-9]+)~', $pathSegment)) {
				if($attraction = \Service\Attraction\Attraction::get($match[1])) {
					$params->attraction = $attraction;
					$params->page = 'attraction';
				}
			} else if($match = Strings::match($pathSegment, '~\.*-r([0-9]+)~')) {
				if($rental = \Service\Rental\Rental::get($match[1])) {
					$params->rental = $rental;
					$params->page = 'rental';
				}
			}
		}

		if(count($httpRequest->query)) {
			foreach ($httpRequest->query as $key => $value) {
				if(!array_key_exists($key, $this->queryParams)) continue;
				$params->query->{$key} = $value;
			}
		}

		$segmentList = $this->getPathSegmentList($pathSegments, $params);
		if($segmentList->count() == count($pathSegments)) {
			$pathSegmentTypesFlip = array_flip($this->pathSegmentTypes);
			foreach ($segmentList as $key => $value) {
				$params->{$pathSegmentTypesFlip[$value->type]} = $value->pathSegment;
			}
		} else {
			// @todo pocet najdenych pathsegmentov sa nezhoduje
			// ak nejake chybaju tak ich skus najst v PathSegmentsOld
		}

		if(!isset($params->page)) {
			$params->page = 'rentalList';
		}

		debug($params);
		return $params;
	}

	public function getPathSegmentList($pathSegments, $params) {
		$criteria = array();
		$criteria['pathSegment'] = $pathSegments;
		$criteria['country'] = array($params->country, 0);
		$criteria['language'] = array($params->language, 0);

		$pathSegmentList = \Service\Routing\PathSegmentList::getBy((array) $criteria, $orderBy = array('type' => 'ASC'));
		debug($pathSegmentList);
		return $pathSegmentList;
	}

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

}