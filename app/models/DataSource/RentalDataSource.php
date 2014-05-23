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
use Routers\BaseRoute;
use Routers\FrontRoute;
use Security\Authenticator;
use Tralandia\Rental\Rentals;

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
	 * @var \Security\Authenticator
	 */
	private $authenticator;

	/**
	 * @var \Nette\Application\Application
	 */
	private $application;

	/**
	 * @var \Tralandia\Rental\Rentals
	 */
	private $rentals;


	/**
	 * @param EntityManager $em
	 * @param \Extras\Books\Phone $phoneBook
	 * @param \Tralandia\Rental\Rentals $rentals
	 * @param \Security\Authenticator $authenticator
	 * @param \Nette\Application\Application $application
	 */
	public function __construct(EntityManager $em, Phone $phoneBook, Rentals $rentals, Authenticator $authenticator, Application $application)
	{
		$this->em = $em;
		$this->phoneBook = $phoneBook;
		$this->authenticator = $authenticator;
		$this->application = $application;
		$this->rentals = $rentals;
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
				$result = $this->rentals->findByEmailOrUserEmail($search);
			} else if (is_numeric($search)) {
				$result = $this->em->getRepository(RENTAL_ENTITY)->findById($search);
			}
		} else {
			$result = $this->em->getRepository(RENTAL_ENTITY)->findByStatus(Rental::STATUS_LIVE, NULL, 30);
		}


		$presenter = $this->application->getPresenter();
		$return = new ArrayHash();
		/** @var $row \Entity\Rental\Rental */
		foreach($result as $key => $row) {
			$owner = $row->getOwner();
			$hash = $this->authenticator->calculateAutoLoginHash($owner);
			$newRow['id'] = $row->getId();
			$newRow['entity'] = $row;

			$newRow['editLink'] = $presenter->link(':Owner:RentalEdit:default', [
					'id' => $row->getId(),
					FrontRoute::PRIMARY_LOCATION => $owner->getPrimaryLocation(),
					FrontRoute::LANGUAGE => $owner->getLanguage(),
					BaseRoute::AUTOLOGIN => $hash,
				]
			);
//			$newRow['editLink'] = $presenter->link(':Admin:Rental:fakeLogin', [
//				'id' => $row->getOwnerId(),
//				'redirect' => $redirect
//			]);
			$return[$key] = ArrayHash::from($newRow);
		}


		return $return;
	}

}
