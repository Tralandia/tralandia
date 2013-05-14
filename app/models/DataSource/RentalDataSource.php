<?php

namespace DataSource;

use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Extras\Books\Email;
use Extras\Books\Phone;
use Nette\Utils\Arrays;
use Nette\Utils\Paginator;
use Nette\Utils\Validators;

class RentalDataSource extends BaseDataSource {

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var \Extras\Books\Phone
	 */
	private $phoneBook;

	/**
	 * @var \Extras\Books\Email
	 */
	private $emailBook;


	/**
	 * @param EntityManager $em
	 * @param \Extras\Books\Phone $phoneBook
	 * @param \Extras\Books\Email $emailBook
	 */
	public function __construct(EntityManager $em, Phone $phoneBook, Email $emailBook)
	{
		$this->em = $em;
		$this->phoneBook = $phoneBook;
		$this->emailBook = $emailBook;
	}


	/**
	 * @inheritdoc
	 */
	public function getData($filter, $order, Paginator $paginator = NULL)
	{
		$search = Arrays::get($filter, 'search', NULL);
		$result = [];
		if($search) {
			if($phone = $this->phoneBook->getOrCreate($search)) {
				$result = $this->em->getRepository(RENTAL_ENTITY)->findByPhone($phone);
			} else if($email = $this->emailBook->getOrCreate($search)) {
				$result = $this->em->getRepository(RENTAL_ENTITY)->findByEmail($email);
			} else if (is_numeric($search)) {
				$result = $this->em->getRepository(RENTAL_ENTITY)->findById($search);
			}
		} else {
			$result = $this->em->getRepository(RENTAL_ENTITY)->findByStatus(Rental::STATUS_LIVE, NULL, 30);
		}

		return $result;
	}

}
