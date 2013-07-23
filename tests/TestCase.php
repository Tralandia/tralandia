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

	/**
	 * @var \SystemContainer|\Nette\DI\Container
	 */
	private $context;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

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
		$this->mockista = new \Mockista\Registry();

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

	protected function tearDown()
	{
		parent::tearDown();
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


	public function findLocation($id)
	{
		if(!is_numeric($id)) {
			return $this->getEm()->getRepository(LOCATION_ENTITY)->findOneByIso($id);
		} else {
			return $this->getEm()->getRepository(LOCATION_ENTITY)->find($id);
		}
	}

	public function findLanguage($id)
	{
		return $this->getEm()->getRepository(LANGUAGE_ENTITY)->find($id);
	}

	public function findPage($id)
	{
		return $this->getEm()->getRepository(PAGE_ENTITY)->find($id);
	}

	public function findRental($id)
	{
		return $this->getEm()->getRepository(RENTAL_ENTITY)->find($id);
	}

	public function findRentalType($id)
	{
		return $this->getEm()->getRepository(RENTAL_TYPE_ENTITY)->find($id);
	}



}
