<?php

namespace Extras\Types;

class Url extends \Nette\Http\Url {

	public function __construct($url = NULL) {
		parent::__construct($url);

		if(!$this->getScheme()) {
			$this->setScheme('http');
		}
	}

	public function getSortUrl() {
		return $this->getAuthority() . $this->getBasePath() . $this->getRelativeUrl();
	}

}