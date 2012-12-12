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
			array('40:26:46.302S', '79:56:55.903E'),
			array('40°26′46″S', '79°56′55″E'),
			array('40d 26′ 46″ S', '79d 56′ 55″ E'),
			array('40.446195S', '79.948862E'),
			array('-40.446195', '79.948862'),
			array('-40° 26.7717', '79° 56.93172'),
		);

		foreach ($testValues as $value) {
			$lat = new \Extras\Types\Latlong($value[0], 'latitude');
			$long = new \Extras\Types\Latlong($value[1], 'longitude');
			$this->assertSame("40°26′46″ S", $lat->toString());
			$this->assertSame("40°26′46″ S", $long->toString());
		}

	}

}