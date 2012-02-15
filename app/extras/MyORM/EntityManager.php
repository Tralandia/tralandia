<?php

namespace MyORM;

use \Doctrine\DBAL\Connection,
	\Doctrine\ORM\Configuration;

class EntityManager extends \Doctrine\ORM\EntityManager {
	/*
    private $config;
    private $conn;
    private $metadataFactory;
    private $repositories = array();
    private $unitOfWork;
    private $eventManager;
    private $hydrators = array();
    private $proxyFactory;
    private $expressionBuilder;
    private $closed = false;
	*/
	protected function __construct(Connection $conn, Configuration $config, \MyORM\EntityManager $eventManager) {
		parent::__construct($conn, $config, $eventManager);
		$this->unitOfWork = new \MyORM\UnitOfWork($this);
	}
	
	public static function create($conn, Configuration $config, \Doctrine\ORM\EntityManager $eventManager = null) {
		if (!$config->getMetadataDriverImpl()) {
			throw ORMException::missingMappingDriverImpl();
		}

		if (is_array($conn)) {
			$conn = \Doctrine\DBAL\DriverManager::getConnection($conn, $config, ($eventManager ?: new EventManager()));
		} else if ($conn instanceof Connection) {
			if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
				throw ORMException::mismatchedEventManager();
			}
		} else {
			throw new \InvalidArgumentException("Invalid argument: " . $conn);
		}

		return new self($conn, $config, $conn->getEventManager());
	}
}