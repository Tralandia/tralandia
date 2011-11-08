<?php

use Nette\DI;

/**
 * @author Branislav Vaculčiak
 */
class Configurator extends Nette\Configurator {

	/**
	 * @param DI\Container $container
	 * @return Kdyby\Doctrine\Container
	 */
	public static function createServiceDoctrine(DI\Container $container) {
		$doctrine = new Kdyby\Doctrine\Container;
		$doctrine->addService('container', $container);
		
		$doctrine->configuration->addCustomStringFunction('DATE_FORMAT', 'DateFormatFunction');
		\Doctrine\DBAL\Types\Type::addType('blob', 'Doctrine\DBAL\Types\BlobType');
		
		$doctrine->entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('BLOB', 'blob');
		$doctrine->entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
		
		return $doctrine;
	}
	
	/**
	 * @param DI\Container $container
	 * @return Authenticator
	 */
	public static function createServiceAuthenticator(DI\Container $container) {
		return new Authenticator($container->doctrine->entityManager->getRepository('User'));
	}
}