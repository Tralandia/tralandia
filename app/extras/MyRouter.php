<?php

namespace Extras;

use Nette,
	Nette\Application,
	Nette\Utils\Strings;

class MyRouter implements Nette\Application\IRouter {
	
	protected $db;
	protected $metadata;
	
	public function __construct(Nette\Database\Connection $database, $metadata) {
		$this->db = $database;
		if (is_string($metadata)) {
			$a = strrpos($metadata, ':');
			if (!$a) {
				throw new Nette\InvalidArgumentException("Second argument must be array or string in format Presenter:action, '$metadata' given.");
			}
			$metadata = array(
				'presenter' => substr($metadata, 0, $a),
				'action' => $a === strlen($metadata) - 1 ? Application\UI\Presenter::DEFAULT_ACTION : substr($metadata, $a + 1),
			);
		}
		$this->metadata = $metadata;
	}


	function match(Nette\Http\IRequest $httpRequest) {

		return new Application\Request(
			$presenter,
			$httpRequest->getMethod(),
			$params,
			$httpRequest->getPost(),
			$httpRequest->getFiles(),
			array(Application\Request::SECURED => $httpRequest->isSecured())
		);
	}

	function constructUrl(Nette\Application\Request $appRequest, Nette\Http\Url $refUrl) {
		return NULL;
	}

}