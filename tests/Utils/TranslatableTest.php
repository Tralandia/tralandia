<?php
namespace Tests\Utils;

use Nette, Extras;
use Routers\BaseRoute;
use Tests\TestCase;
use Tralandia\Dictionary\Translatable;


/**
 * @backupGlobals disabled
 */
class TranslatableTest extends TestCase
{

	public function testBase()
	{
		$t = Translatable::from('foo ', ['bar'], ' baz');

		$translator = $this->getContext()->getService('translatorFactory');
		$translator = $translator->create($this->findLanguage(38));

		$this->assertEquals('foo bar baz',$translator->translate($t));
	}

}
