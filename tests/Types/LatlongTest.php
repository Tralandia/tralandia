<?php

namespace Test\Emailer;

use PHPUnit_Framework_TestCase, Nette, Extras;


require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class LatlongTest extends \PHPUnit_Framework_TestCase
{

	public function testCompiler() {
		$testValues = array(
			array('40:26:46S', '79:56:55E'),
			array('40:26:46.302S', '79:56:55.403E'),
			array('40°26′46″S', '79°56′55″E'),
			array('40d 26′ 46″ S', '79d 56′ 55″ E'),
			array('40.446195S', '79.948702E'),
			array('-40.446195', '79.948702'),
			array('-40° 26.7717', '79° 56.913'),
		);

		foreach ($testValues as $value) {
			$lat = new \Extras\Types\Latlong($value[0], 'latitude');
			$long = new \Extras\Types\Latlong($value[1], 'longitude');
			echo("\n".$value[0].' : '.$value[1]);
			$this->assertSame("40°26′46″S", (string)$lat);
			$this->assertSame("79°56′55″E", (string)$long);
		}

	}

}