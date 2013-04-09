<?php
namespace Tests;

use Nette, Extras;


/**
 *
 * @backupGlobals disabled
 */
class Html2texyTest extends TestCase
{

	/**
	 * @var \Html2texy
	 */
	protected $html2texy;


	protected function setUp() {
		$this->html2texy = $this->getContext()->html2texy;
	}

	public function testBase()
	{
		$html = file_get_contents(__DIR__.'/htmlSource001.html');
		$html2texy = $this->html2texy;
		$texy = $html2texy->convert($html);

		$this->assertStringEqualsFile(__DIR__.'/texy001.expect', $texy);
	}

}
