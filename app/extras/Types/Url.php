<?php

namespace Extras\Types;

class Url extends \Nette\Http\Url implements IContact {

	public function __construct($url = NULL) {
		parent::__construct($url);

		if(!$this->getScheme()) {
			$this->setScheme('http');
		}
	}

	public function getShortUrl() {
		return $this->getAuthority() . $this->getBasePath() . $this->getRelativeUrl();
	}

	public function __toString() {
		$t = parent::__toString();
		if ($t == 'http://') {
			return "";
		} else {
			return $t;			
		}
	}

	public function encode() {
		return \Nette\Utils\Json::encode(array('url' => $this->__toString()));
	}

}