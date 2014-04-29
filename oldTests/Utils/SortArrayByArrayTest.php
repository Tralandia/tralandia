<?php
namespace Tests\Utils;

use Nette, Extras;
use Routers\BaseRoute;
use Tests\TestCase;


/**
 * @backupGlobals disabled
 */
class SortArrayByArrayTest extends TestCase
{

	public function testBase()
	{
		$array = [1 => 1, 2, 3];
		$order = [2, 1, 3];
		$array = \Tools::sortArrayByArray($array, $order);

		$expected = [2 => 2, 1 => 1, 3 => 3];
		$this->assertSame($expected, $array);
	}


	public function testWithCallback()
	{
		$array = [['id' => 1],['id' => 2],['id' => 3]];
		$order = [3, 1, 2];
		$array = \Tools::sortArrayByArray($array, $order, function($value) {
			return $value['id'];
		});

		$expected = [
			3 => ['id' => 3],
			1 => ['id' => 1],
			2 => ['id' => 2],
		];

		$this->assertSame($expected, $array);
	}
}
