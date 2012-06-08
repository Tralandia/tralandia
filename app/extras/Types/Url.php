<?php

namespace Extras\Types;

class Url extends \Nette\Http\Url implements IContact {

	public function __construct($url = NULL) {
		$url = is_array($url) ? array_shift($url) : $url;
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

	public function toArray() {
		return array('url' => (string) $this);
	}

	public function toFormValue() {
		return 'url~' . (string) $this;
	}

	public function encode() {
		return \Nette\Utils\Json::encode(array('url' => $this->toArray()));
	}

	public function getUnifiedFormat() {
		return (string) $this;
	}
}