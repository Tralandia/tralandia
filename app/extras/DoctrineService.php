<?php

namespace Extras;

use Nette\DI, Doctrine, Symfony, Nella;

class DoctrineService {
	
	/**
	 * @param DI\Container $container
	 * @param array $connectionOptions
	 * @param string $entityDirs
	 * @param string $proxyDir
	 * @return Doctrine\ORM\EntityManager
	 */
	public static function factory(DI\Container $container, $connectionOptions, $entityDirs, $proxyDir) {
		if (!$container->parameters['productionMode']) {
			$cache = new \Doctrine\Common\Cache\ArrayCache;
		} else {
			$cache = new \Doctrine\Common\Cache\ApcCache;
		}

		$config = new Doctrine\ORM\Configuration;
		$config->setMetadataCacheImpl($cache);

		$config->setSQLLogger(Nella\ConnectionPanel::register());

		$driverImpl = $config->newDefaultAnnotationDriver($entityDirs);
		
		$config->setMetadataDriverImpl($driverImpl);
		//$config->setQueryCacheImpl($cache);
		$config->setProxyDir($proxyDir);
		$config->setProxyNamespace('Entities\Proxies');

		$entityManager = Doctrine\ORM\EntityManager::create($connectionOptions, $config);
		$entityManager->getConnection()->setCharset('utf8');
		//$entityManager->getConnection()->setCollate('utf8_unicode_ci');
/*
if ($applicationMode == "development") {
    $config->setAutoGenerateProxyClasses(true);
} else {
    $config->setAutoGenerateProxyClasses(false);
}
*/

		//$doctrine->configuration->addCustomStringFunction('DATE_FORMAT', 'DateFormatFunction');
		//$doctrine->configuration->addCustomStringFunction("MATCH_AGAINST", "MatchAgainst");
		//$config->addCustomStringFunction("GROUP_CONCAT", "GroupConcat"); 
		//Doctrine\DBAL\Types\Type::addType('blob', 'Doctrine\DBAL\Types\BlobType');

		Doctrine\DBAL\Types\Type::addType('longtext', 'Extras\Doctrine\Types\LongText');
		
		//$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('BLOB', 'blob');
		//$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
		$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('LONGTEXT', 'longtext');
		
		return $entityManager;
	}

	/**
	 * @param Doctrine\ORM\EntityManager $entityManager
	 * @return Symfony\Component\Console\Application
	 */
	public static function factoryConsole(Doctrine\ORM\EntityManager $entityManager) {
		$cli = new Symfony\Component\Console\Application('Doctrine Command Line Interface', Doctrine\Common\Version::VERSION);
		$cli->setCatchExceptions(TRUE);

		$helperSet = $cli->getHelperSet();
		$helperSet->set(new Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($entityManager->getConnection()), 'db');
		$helperSet->set(new Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager), 'em');

		$cli->addCommands(array(
		    // DBAL Commands
		    new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
		    new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

		    // ORM Commands
		    new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
		    new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
		));

		return $cli;
	}
}