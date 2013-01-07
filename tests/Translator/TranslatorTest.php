<?php
namespace Tests\Translator;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class TranslatorTest extends \Tests\TestCase
{

	/**
	 * @var \Extras\Translator
	 */
	protected $translator;

	protected function setUp() {
		$this->translator = $this->getContext()->translator;
	}

	public function testName() {



	}

}