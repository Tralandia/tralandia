<?php

namespace Extras\PhoneBook;

use Nette, Extras;

class PhoneBook extends Nette\Object {

	/** @var Extras\Models\Repository\RepositoryAccessor */
	private $phoneRepository;

	/**
	 * @param Extras\Models\Repository\RepositoryAccessor $phoneRepository
	 */
	public function __construct(Extras\Models\Repository\RepositoryAccessor $phoneRepository) {
		$this->phoneRepository = $phoneRepository;
	}

	/**
	 * @param string $number
	 * @return bool
	 */
	public function find($number) {
		return false;
	}

	/**
	 * @param string $number
	 * @return bool
	 */
	public function getOrCreate($number) {
		return false;
	}
}