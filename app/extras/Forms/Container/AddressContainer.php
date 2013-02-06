<?php

namespace Extras\Forms\Container;

use Nette\Application\UI\Link;

class AddressContainer extends BaseContainer implements \Nette\Application\UI\ISignalReceiver
{

	public function __construct($locations = NULL)
	{
		parent::__construct();

		$this->addText('address', '#Address');
		$this->addText('locality', '#Locality');
		$this->addText('postalCode', '#Postal Code');
		$this->addSelect('primaryLocation', '#Primary location', $locations);
	}

	public function getMainControl()
	{
		return $this['address'];
	}

	/**
	 * @param  string
	 *
	 * @return void
	 */
	function signalReceived($signal)
	{
		// TODO: Implement signalReceived() method.
	}

}
