<?php
namespace Tests\Utils;

use Nette, Extras;
use Routers\BaseRoute;
use Tests\TestCase;


/**
 * @backupGlobals disabled
 */
class ArrayMapTest extends TestCase
{

	public function testBase()
	{
		$array = [['id' => 1],['id' => 2],['id' => 3]];

		$array = \Tools::arrayMap($array, 'id');

		$expected = [1,2,3];
		$this->assertSame($expected, $array);
	}


	public function testWithValueCallback()
	{
		$array = [['id' => 1],['id' => 2],['id' => 3]];

		$array = \Tools::arrayMap($array, function($value) {
			return $value['id'];
		});

		$expected = [1,2,3];

		$this->assertSame($expected, $array);
	}

	public function testWithKeyCallback()
	{
		$array = [['id' => 1],['id' => 2],['id' => 3]];

		$array = \Tools::arrayMap($array, function($key, $value) {
			return $value['id'];
		}, 'id');

		$expected = [1 => 1,2,3];

		$this->assertSame($expected, $array);
	}
}
