<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 17/03/14 13:06
 */

namespace Routers;


use Kdyby\Doctrine\EntityManager;
use Nette;
use Nette\Application\Request;
use Nette\Application\Routers\Route;
use Tralandia\BaseDao;

class PersonalSiteRoute extends Nette\Object implements Nette\Application\IRouter
{

	/**
	 * @var \Nette\Application\Routers\Route
	 */
	protected $route;

	/**
	 * @var BaseDao
	 */
	protected $rentalDao;

	/**
	 * @var BaseDao
	 */
	protected $languageDao;


	public function __construct($mask, $metadata = array(), EntityManager $em)
	{
		$this->route = new Route($mask, $metadata);
		$this->rentalDao = $em->getRepository(RENTAL_ENTITY);
		$this->languageDao = $em->getRepository(LANGUAGE_ENTITY);
	}


	/**
	 * Maps HTTP request to a Request object.
	 * @return Request|NULL
	 */
	function match(Nette\Http\IRequest $httpRequest)
	{
		$appRequest = $this->route->match($httpRequest);

		if ($appRequest) {
			$params = $appRequest->getParameters();

			$domain = $httpRequest->getUrl()->getHost();
			if($rental = $this->rentalDao->findOneBy(['personalSiteUrl' => $domain])) {
				$params['rental'] = $rental;
				$params[BaseRoute::PRIMARY_LOCATION] = $rental->getPrimaryLocation();
				if($params[BaseRoute::LANGUAGE]) {
					$params[BaseRoute::LANGUAGE] = $this->languageDao->findOneBy(['iso' => $params[BaseRoute::LANGUAGE]]);
				} else {
					$params[BaseRoute::LANGUAGE] = $rental->getPrimaryLocation()->getDefaultLanguage();
				}
			} else {
				return null;
			}

			$appRequest->setParameters($params);

			return $appRequest;
		}

		return NULL;
	}


	/**
	 * Constructs absolute URL from Request object.
	 * @return string|NULL
	 */
	function constructUrl(Request $appRequest, Nette\Http\Url $refUrl)
	{
		$appRequest = clone $appRequest;
		$params = $appRequest->getParameters();

		$domain = $params['rental']->personalSiteUrl;
		$params['rentalSlug'] = strstr($domain, '.', true);

		$appRequest->setParameters($params);

		$url = $this->route->constructUrl($appRequest, $refUrl);

		if($url) {
			return $url;
		}

		return NULL;
	}
}


interface IPersonalSiteRouteFactory {
	public function create($mask, $metadata);
}
