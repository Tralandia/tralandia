<?php

namespace Config;

use  Nette, Extras;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class ConfiguratorTest extends \Tests\TestCase
{
	public $configPath;

	protected function setUp() {
		$this->configPath = INCLUDE_DIR . '/Currency.neon';
	}

	public function testFormOptions() {
		$configurator = new Extras\Config\Configurator($this->configPath);

		$this->assertInternalType('array', $configurator->getForm());
		$this->assertNotEmpty($configurator->getForm());

		foreach ($configurator->getForm() as $field) {
			$this->assertInstanceOf('Extras\Config\Form\IField', $field);
			$this->assertInternalType('string', $field->getName());
			$this->assertInternalType('string', $field->getLabel());
			$this->assertInternalType('string', $field->getType());
			$this->assertInternalType('string', $field->getDescription());
			$this->assertInternalType('string', $field->getClass());
		}
	}

	public function testGridOptions() {
		// TODO: dorobit test nastaveni pre grid
	}

	public function testPresenterOptions() {
		// TODO: dorobit test nastaveni pre presenter a template
	}
}