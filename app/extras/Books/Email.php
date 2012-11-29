<?php

namespace Extras\Books;

use Nette, Extras, Entity;

/**
 * Treda sluzi ako databaza unikatnych emailov.
 */
class Email extends Nette\Object {

	/** @var Extras\Models\Repository\RepositoryAccessor */
	private $emailRepository;

	/**
	 * @param Extras\Models\Repository\RepositoryAccessor $emailRepository
	 */
	public function __construct(Extras\Models\Repository\RepositoryAccessor $emailRepository) {
		$this->emailRepository = $emailRepository;
	}

	/**
	 * Vyhlada email v DB
	 * @param string $email
	 * @return Entity\Contacts\Email|false
	 */
	public function find($email) {
		return $this->emailRepository->get()->findOneByValue($email);
	}

	/**
	 * Skusi najst email, ak nenajde vytvori novy a vrati jeho zaznam
	 * @param string $value
	 * @return Entity\Contacts\Email
	 */
	public function getOrCreate($value) {
		if (!$email = $this->find($value)) {
			if (!$this->isValid($value)) {
				throw new \Exception('Email nie je validny');
			}
			$email = $this->emailRepository->get()->createNew();
			$email->setValue($value);

			$this->emailRepository->get()->persist($email);
			$this->emailRepository->get()->flush($email);
		}

		return $email;
	}

	/**
	 * Je email validny
	 * @param string $email
	 * @return bool
	 */
	public function isValid($email) {
		return Nette\Utils\Validators::isEmail($email);
	}
}