<?php

namespace Extras\Books;

use Nette, Extras, Entity;

/**
 * Treda sluzi ako databaza unikatnych emailov.
 */
class Address extends Nette\Object {

	/** @var Extras\Models\Repository\RepositoryAccessor */
	private $addressRepository;

	/** @var string */
	private $serviceUrl = 'http://tra-devel.soft1.sk:8080/phonenumberparser?';

	/**
	 * @param Extras\Models\Repository\RepositoryAccessor $addressRepository
	 */
	public function __construct(Extras\Models\Repository\RepositoryAccessor $addressRepository) {
		$this->addressRepository = $addressRepository;
	}
}