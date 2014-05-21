<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 29/04/14 08:33
 */

namespace TralandiaTests;


use Nette;

class TestCase extends \Tester\TestCase
{

	/**
	 * @var \SystemContainer|Nette\DI\Container
	 */
	protected $dic;

	public function __construct($container)
	{
		$this->dic = $container;
	}


}
