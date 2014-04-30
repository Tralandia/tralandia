<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 8/6/13 12:46 PM
 */

namespace Tests\Utils;


use Nette;
use Tests\TestCase;

/**
 * @backupGlobals disabled
 */
class PeriodTest extends TestCase
{


	public function testBase()
	{
		$expected = \Tools::getPeriods();
		$array = \Tools::getPeriods2();
		$this->assertEquals($expected, $array);
	}


	public function testDateTime()
	{
		$expected = \Tools::getPeriods();
		$array = \Tools::getPeriods2();
		$expected = $this->convertToDateTime($expected);
		$array = $this->convertToDateTime($array);
		$this->assertEquals($expected, $array);
	}


	public function convertToDateTime($array)
	{
		foreach($array as $key => $value) {
			$array[$key][0] = Nette\DateTime::from($value[0]);
			$array[$key][1] = Nette\DateTime::from($value[1]);
		}

		return $array;
	}



}
