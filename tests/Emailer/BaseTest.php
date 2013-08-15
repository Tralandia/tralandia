<?php

namespace Tests\Emailer;

use Entity\Language;
use Entity\Location\Location;
use Environment\Environment;
use Mail\ICompilerFactory;
use Nette, Extras;

/**
 * @backupGlobals disabled
 */
class BaseTest extends \Tests\TestCase
{
	/**
	 * @var \Entity\Email\Template
	 */
	protected $template;

	/**
	 * @var ICompilerFactory
	 */
	protected $emailCompilerFactory;

	/**
	 * @var \Environment\IEnvironmentFactory
	 */
	protected $environmentFactory;

	protected function setUp()
	{
		$layoutHtml = 'layout {include #content}';
		$layout = $this->mockista->create('\Entity\Email\Layout', [
			'getHtml' => $layoutHtml,
		]);

		$templateSubject = $this->createPhrase('\Entity\Email\Template:subject', "Ahoj\nToto je preklad");
		$templateBody = $this->createPhrase('\Entity\Email\Template:body', "Ahoj\nToto je preklad");

		$template = $this->mockista->create('\Entity\Email\Template', [
			'getLayout' => $layout,
			'getBody' => $templateBody,
			'getSubject' => $templateSubject,
		]);

		$this->template = $template;

		$this->emailCompilerFactory = $this->getContext()->getByType('\Mail\ICompilerFactory');
		$this->environmentFactory = $this->getContext()->getByType('\Environment\IEnvironmentFactory');
	}


	/**
	 * @param Location $location
	 * @param Language $language
	 *
	 * @return \Mail\Compiler
	 */
	protected function createCompiler(Location $location, Language $language)
	{
		$environment = $this->environmentFactory->create($location, $language);
		$emailCompiler = $this->emailCompilerFactory->create($environment);
		return $emailCompiler;
	}

	public function testBase()
	{
		$emailCompiler = $this->createCompiler($this->findLocation('sk', TRUE, 'iso'), $this->findLanguage('sk', TRUE, 'iso'));
		$emailCompiler->setTemplate($this->template);

		$body = $emailCompiler->compileBody();

		$expected = '';
		$this->assertEquals($expected, $body);
	}

}
