<?php

namespace Extras\Config;

use Nette;

class PresenterExtension extends Nette\Config\CompilerExtension
{
	public function loadConfiguration() {
		$config = $this->getConfig();

debug($config);
	}
}