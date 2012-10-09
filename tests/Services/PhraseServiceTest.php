<?php

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class PhraseServiceTest extends PHPUnit_Framework_TestCase
{
	public $context;
	public $model;
	public $language;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
		$this->model = $this->context->model;
		$this->language = $this->context->environment->getLanguage();
	}

	public function testChangeTranslation() {
		$entity = $this->model->getRepository('Entity\Currency')->find(2);
		$service = new Service\Dictionary\Phrase($this->model, $entity->name);
		
		// vytiahnem preklad, ulozim si jeho aktualnu hodnotu
		$this->assertInstanceOf('Entity\Dictionary\Translation', $translate = $service->getTranslate($this->language));
		$this->assertInternalType('string', $oldTranslation = $translate->translation);

		// zmenim preklad a ulozim zmenu
		$translate->translation = 'nová hodnota 9875';
		$this->model->flush();

		// vytiahnem zmeneny preklad
		$entity = $this->model->getRepository('Entity\Currency')->find(2);
		$service = new Service\Dictionary\Phrase($this->model, $entity->name);
		
		// overujem ci sa zmena preniesla do D
		$this->assertInstanceOf('Entity\Dictionary\Translation', $translate = $service->getTranslate($this->language));
		$this->assertInternalType('string', $translate->translation);
		$this->assertSame('nová hodnota 9875', $translate->translation);

		// znova zmenit preklad na povodnu hodnotu a ulozim
		$translate->translation = $oldTranslation;
		$this->model->flush();
	}

	/**
	 * @expectedException Nette\FatalErrorException
	 */
	public function testFail() {
		$entity = $this->model->getRepository('Entity\Currency')->find(0);
		$service = new Service\Dictionary\Phrase($this->model, $entity->name);
	}
}