<?php
namespace Tests;

use Nette, Extras;


/**
 *
 * @backupGlobals disabled
 */
class ShareLinksTest extends TestCase
{

	/**
	 * @var \ShareLinks
	 */
	protected $shareLinks;


	protected function setUp() {
		$this->shareLinks = $this->getContext()->shareLinks;
	}


	public function testGooglePlus()
	{
		$shareLinks = $this->shareLinks;

		$tag = $shareLinks->getGooglePlusShareTag('http:://www.tralandia.com');
		$expected = '<a class="socialite googleplus-one" href="https://plus.google.com/share?url=http:://www.tralandia.com" data-size="medium" rel="nofollow" target="_blank"></a>';
		$this->assertEquals($expected, "$tag");

		$tag = $shareLinks->getGooglePlusProfileShareTag();
		$expected = '<a class="socialite googleplus-one" href="https://plus.google.com/share?url=https://plus.google.com/115691730730719504032/posts" data-size="medium" rel="nofollow" target="_blank"></a>';
		$this->assertEquals($expected, "$tag");
	}


	public function testFacebook()
	{
		$shareLinks = $this->shareLinks;

		$tag = $shareLinks->getFacebookShareTag('http:://www.tralandia.com');
		$expected = '<g:plusone size="medium" href="http:://www.tralandia.com" lang="sk"></g:plusone>';
		$this->assertEquals($expected, "$tag");
	}


}
