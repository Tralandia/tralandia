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
	 * @param string $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		$this->context = Nette\Environment::getContext();

		parent::__construct($name, $data, $dataName);
	}



	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	public function getContext()
	{
		return $this->context;
	}


	/********************* Nette Forms *********************/


	/**
	 * @param \Nette\Application\UI\Form $form
	 * @param array $values
	 */
	public function submitForm(UI\Form $form, array $values = array())
	{
		$get = $form->getMethod() !== UI\Form::POST ? $values : array();
		$post = $form->getMethod() === UI\Form::POST ? $values : array();
		list($post, $files) = $this->separateFilesFromPost($post);

		$presenter = new Tools\UIFormTestingPresenter($form);
		$this->getContext()->callMethod(array($presenter, 'injectPrimary'));
		return $presenter->run(new Nette\Application\Request(
			'presenter',
			strtoupper($form->getMethod()),
			array('do' => 'form-submit', 'action' => 'default') + $get,
			$post,
			$files
		));
	}



	/**
	 * @param array $post
	 * @param array $files
	 *
	 * @return array
	 */
	private function separateFilesFromPost(array $post, array $files = array())
	{
		foreach ($post as $key => $value) {
			if (is_array($value)) {
				list($pPost, $pFiles) = $this->separateFilesFromPost($value);
				unset($post[$key]);

				if ($pPost) {
					$post[$key] = $pPost;
				}
				if ($pFiles) {
					$files[$key] = $pFiles;
				}
			}

			if ($value instanceof Nette\Http\FileUpload) {
				$files[$key] = $value;
				unset($post[$key]);
			}
		}

		return array($post, $files);
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

}