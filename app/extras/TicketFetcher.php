<?php

namespace Extras;

class TicketFetcher extends \Nette\Object {

	protected $fetcher;

	public function __construct($fetcher) {
		$this->fetcher = $fetcher;
	}

	public function getMessages() {
		return call_user_func_array(array($fetcher, 'getMessages'), func_get_args());
	}

}