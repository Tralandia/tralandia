<?php

use Nette\Diagnostics\Debugger;

define('CLI_DIR', __DIR__);
define('WWW_DIR', CLI_DIR);
define('APP_DIR', CLI_DIR . '/../app');
define('LIBS_DIR', CLI_DIR . '/../libs');
define('TEMP_DIR', CLI_DIR . '/../temp');

// loaders
require_once LIBS_DIR . '/Nette/loader.php';

// Create Configurator
require_once APP_DIR . '/Configurator.php';

// debugger
Debugger::enable();
Debugger::$strictMode = TRUE;

// configuration
$configurator = new Configurator();
$configurator->loadConfig(APP_DIR . '/config.neon');
$configurator->container->doctrine->cache->deleteAll(); // without this the work is nightmare

$cli = new Symfony\Component\Console\Application('Doctrine Command Line Interface', Doctrine\Common\Version::VERSION);
$cli->setCatchExceptions(TRUE);

$entityManager = $configurator->container->doctrine->entityManager;

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
$cli->run();
