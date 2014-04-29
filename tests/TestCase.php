<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2012 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Tests;

use Nette;
use Nette\ObjectMixin;
use Nette\PhpGenerator as Code;
use Nette\Utils\Strings;
use Nette\Application\UI;


/**
 * @author Filip Procházka <filip@prochazka.su>
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{

	use \TFindEntityHelper;

	/**
	 * @var \SystemContainer|\Nette\DI\Container
	 */
	private $context;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var \LeanMapper\Connection
	 */
	private $db;

	/** @var \Mockista\Registry */
	protected $mockista;

	/**
	 * @param string $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		$this->context = Nette\Environment::getContext();
		$this->em = $this->context->getByType('\Doctrine\ORM\EntityManager');
		$this->db = $this->context->getService('testDb');
		$this->mockista = new \Mockista\Registry();
		$this->initializeFindEntityHelper($this->getEm());

		parent::__construct($name, $data, $dataName);
	}



	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	public function getContext()
	{
		return $this->context;
	}

	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	public function getEm()
	{
		return $this->em;
	}


	protected function getDb()
	{
		return $this->db;
	}


	protected function newDataSet($file, array $variables = NULL)
	{
		$this->executeSqlFile(__DIR__ . '/truncate.sql');
		$this->executeSqlFile($file, $variables);
	}

	protected function executeSqlFile($file, array $variables = NULL)
	{
		if($variables) {
			$file = $this->createTempSqlFile($file, $variables);
		}
		$this->getDb()->loadFile($file);
	}

	private function createTempSqlFile($file, array $variables)
	{
		$content = file_get_contents($file);
		$content = str_replace(array_keys($variables), array_values($variables), $content);
		$newFile = $file . '-temp';
		file_put_contents($newFile, $content, LOCK_EX);
		return $newFile;
	}


	protected function tearDown()
	{
		parent::tearDown();
		\Mockery::close();
		$this->mockista->assertExpectations();
	}



	/********************* Exceptions handling *********************/


	/**
	 * This method is called when a test method did not execute successfully.
	 *
	 * @param \Exception $e
	 */
	protected function onNotSuccessfulTest(\Exception $e)
	{
		if (!$e instanceof \PHPUnit_Framework_AssertionFailedError) {
			Nette\Diagnostics\Debugger::log($e);
		}

		parent::onNotSuccessfulTest($e);
	}


	/********************* Nette\Object behaviour ****************d*g**/



	/**
	 * @return \Nette\Reflection\ClassType
	 */
	public static function getReflection()
	{
		return new Nette\Reflection\ClassType(get_called_class());
	}



	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function &__get($name)
	{
		return ObjectMixin::get($this, $name);
	}



	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		ObjectMixin::set($this, $name, $value);
	}



	/**
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 */
	public function __call($name, $args)
	{
		return ObjectMixin::call($this, $name, $args);
	}



	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function __isset($name)
	{
		return ObjectMixin::has($this, $name);
	}



	/**
	 * @param string $name
	 */
	public function __unset($name)
	{
		ObjectMixin::remove($this, $name);
	}


	/* ------------- HELPERS ---------------- */

	/**
	 * @param $type
	 * @param null $sourceTranslation
	 *
	 * @return \Entity\Phrase\Phrase
	 */
	public function createPhrase($type, $sourceTranslation = NULL)
	{
		/** @var $phraseCreator \Service\Phrase\PhraseCreator */
		$phraseCreator = $this->getContext()->getByType('\Service\Phrase\PhraseCreator');
		$phrase = $phraseCreator->create($type, $sourceTranslation);

		return $phrase;
	}


}
