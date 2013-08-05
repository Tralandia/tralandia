<?php

namespace DataSource;

use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Extras\Books\Email;
use Extras\Books\Phone;
use Nette\Application\Application;
use Nette\ArrayHash;
use Nette\Utils\Arrays;
use Nette\Utils\Paginator;
use Nette\Utils\Validators;
use Routers\FrontRoute;
use Security\Authenticator;

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
	 * @var \Security\Authenticator
	 */
	private $authenticator;

	/**
	 * @var \Nette\Application\Application
	 */
	private $application;


	/**
	 * @param EntityManager $em
	 * @param \Extras\Books\Phone $phoneBook
	 * @param \Extras\Books\Email $emailBook
	 * @param \Security\Authenticator $authenticator
	 * @param \Nette\Application\Application $application
	 */
	public function __construct(EntityManager $em, Phone $phoneBook, Email $emailBook, Authenticator $authenticator, Application $application)
	{
		$this->em = $em;
		$this->phoneBook = $phoneBook;
		$this->emailBook = $emailBook;
		$this->authenticator = $authenticator;
		$this->application = $application;
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
			} else if(Validators::isEmail($search)) {
				$result = $this->em->getRepository(RENTAL_ENTITY)->findByEmailOrUserEmail($search);
			} else if (is_numeric($search)) {
				$result = $this->em->getRepository(RENTAL_ENTITY)->findById($search);
			}
		} else {
			$result = $this->em->getRepository(RENTAL_ENTITY)->findByStatus(Rental::STATUS_LIVE, NULL, 30);
		}


		$presenter = $this->application->getPresenter();
		$hash = $this->authenticator->calculateAutoLoginHash($presenter->getUser()->getEntity());
		$return = new ArrayHash();
		foreach($result as $key => $row) {
			$newRow['id'] = $row->getId();
			$newRow['entity'] = $row;
			$newRow['editLink'] = $presenter->link(':Owner:Rental:edit', [
				'id' => $row->getId(),
				FrontRoute::PRIMARY_LOCATION => $row->getPrimaryLocation(),
				FrontRoute::AUTOLOGIN => $hash]
			);
			$return[$key] = ArrayHash::from($newRow);
		}


		return $return;
	}

}
