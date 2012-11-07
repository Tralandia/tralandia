<?php

namespace Extras\Config;

use Nette;

class Configurator2 extends Nette\Config\Configurator {

	/**
	 * @param string
	 */
	public function __construct($file, $section = 'common') {
		parent::__construct();
		$this->addParameters(array(
			'container' => array(
				'class' => 'Container' . substr(md5($file), 0, 4)
			),
			'name' => 'nieco'
		));
		
		$this->setTempDirectory(TEMP_DIR)->addConfig($file, $section);
		$this->onCompile[] = function ($configurator, $compiler) {
			$compiler->addExtension('presenter', new PresenterExtension);
		};
		$this->onCompile[] = function ($configurator, $compiler) {
			$compiler->addExtension('form', new FormExtension);
		};
//		$c = $this->createContainer();

	}
}