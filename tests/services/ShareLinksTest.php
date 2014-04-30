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
		$expected = '<a class="socialite googleplus-one" href="https://plus.google.com/share?url=http%3A%3A%2F%2Fwww.tralandia.com" data-href="http:://www.tralandia.com" data-size="medium" rel="nofollow" target="_blank"></a>';
		$this->assertEquals($expected, "$tag");

		$tag = $shareLinks->getGooglePlusProfileShareTag();
		$expected = '<a class="socialite googleplus-one" href="https://plus.google.com/share?url=https%3A%2F%2Fplus.google.com%2F115691730730719504032%2Fposts" data-href="https://plus.google.com/115691730730719504032/posts" data-size="medium" rel="nofollow" target="_blank"></a>';
		$this->assertEquals($expected, "$tag");
	}


	public function testFacebook()
	{
		$shareLinks = $this->shareLinks;

		$tag = $shareLinks->getFacebookShareTag('http:://www.tralandia.com', 'Custom text');
		$expected = '<a class="socialite facebook-like" href="http://www.facebook.com/sharer.php?u=http%3A%3A%2F%2Fwww.tralandia.com&amp;t=Custom+text" data-href="http:://www.tralandia.com" data-layout="button_count" data-send="false" data-width="60" data-show-faces="false" rel="nofollow" target="_blank"></a>';
		$this->assertEquals($expected, "$tag");
	}


	public function testTwitter()
	{
		$shareLinks = $this->shareLinks;

		$tag = $shareLinks->getTwitterShareTag('http:://www.tralandia.com', 'Custom text');
		$expected = '<a class="socialite twitter-share" href="http://twitter.com/share" data-url="http:://www.tralandia.com" data-text="Custom text" data-count="none" rel="nofollow" target="_blank"></a>';
		$this->assertEquals($expected, "$tag");
	}


	public function testPinterest()
	{
		$shareLinks = $this->shareLinks;

		$tag = $shareLinks->getPinterestShareTag('http:://www.tralandia.com', 'Custom text', 'http://www.tralandia.com/u/32/13539183882248.jpg');
		$expected = '<a class="socialite pinterest-pinit" href="http://pinterest.com/pin/create/button/?url=http%3A%3A%2F%2Fwww.tralandia.com&amp;media=http%3A%2F%2Fwww.tralandia.com%2Fu%2F32%2F13539183882248.jpg&amp;description=Custom+text" data-count-layout="horizontal" rel="nofollow" target="_blank"></a>';
		$this->assertEquals($expected, "$tag");
	}


}
