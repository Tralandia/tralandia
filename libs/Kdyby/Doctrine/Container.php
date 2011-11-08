<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2011 Filip Procházka (filip.prochazka@kdyby.org)
 *
 * @license http://www.kdyby.org/license
 */

/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2010 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nellacms.com
 */

namespace Kdyby\Doctrine;

use Doctrine;
use Kdyby;
use Nette;



/**
 * @author Patrik Votoček
 * @author Filip Procházka
 *
 * @property-read Nette\DI\Container $container
 * @property-read Cache $cache
 * @property-read Panel $logger
 * @property-read Doctrine\ORM\Configuration $configurator
 * @property-read Doctrine\ORM\Mapping\Driver\AnnotationDriver $annotationDriver
 * @property-read Doctrine\DBAL\Event\Listeners\MysqlSessionInit $mysqlSessionInitListener
 * @property-read Doctrine\Common\EventManager $eventManager
 * @property-read Doctrine\ORM\EntityManager $entityManager
 */
class Container extends Nette\DI\Container
{

	/**
	 * @return Cache
	 */
	protected function createServiceCache()
	{
		return new Cache($this->container->cacheStorage);
	}



	/**
	 * @return Panel
	 */
	protected function createServiceLogger()
	{
		return Panel::register();
	}



	/**
	 * @return Doctrine\ORM\Configuration
	 */
	protected function createServiceConfiguration()
	{
		$config = new Doctrine\ORM\Configuration;

		// Cache
		$config->setMetadataCacheImpl($this->hasService('metadataCache') ? $this->metadataCache : $this->cache);
		$config->setQueryCacheImpl($this->hasService('queryCache') ? $this->queryCache : $this->cache);

		// Metadata
		$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver($this->container->params['entityDirs']));

		// Proxies
		$config->setProxyDir($this->container->params['proxiesDir']);
		$config->setProxyNamespace(@$this->container->params['proxyNamespace'] ?: 'App\Models\Proxies');

		if ($this->container->params['productionMode']) {
			$config->setAutoGenerateProxyClasses(FALSE);
		} else {
			$config->setAutoGenerateProxyClasses(FALSE);
			$config->setSQLLogger($this->logger);
		}

		return $config;
	}



	/**
	 * @return Doctrine\DBAL\Event\Listeners\MysqlSessionInit
	 */
	protected function createServiceMysqlSessionInitListener()
	{
		return new Doctrine\DBAL\Event\Listeners\MysqlSessionInit($this->container->params['database']['charset']);
	}



	/**
	 * @return Doctrine\Common\EventManager
	 */
	protected function createServiceEventManager()
	{
		return new Doctrine\Common\EventManager;
	}



	/**
	 * @return Doctrine\ORM\EntityManager
	 */
	protected function createServiceEntityManager()
	{
		$database = $this->container->params['database'];

		if (key_exists('driver', $database) && $database['driver'] == "pdo_mysql" && key_exists('charset', $database)) {
			$this->eventManager->addEventSubscriber($this->mysqlSessionInitListener);
		}

		$this->freeze();
		return Doctrine\ORM\EntityManager::create((array)$database, $this->configuration, $this->eventManager);
	}



	/**
	 * @return Doctrine\ORM\EntityManager
	 */
	public function getEntityManager()
	{
		return $this->entityManager;
	}

}
