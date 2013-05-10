<?php

namespace DataSource;

use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Nette\Utils\Arrays;
use Nette\Utils\Paginator;
use Nette\Utils\Validators;

class RentalDataSource extends BaseDataSource {

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;


	/**
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	/**
	 * @inheritdoc
	 */
	public function getData($filter, $order, Paginator $paginator = NULL)
	{
		$search = Arrays::get($filter, 'search', NULL);
		$result = [];
		if($search) {
			if(is_numeric($search)) {
				$result = $this->em->getRepository(RENTAL_ENTITY)->findById($search);
			}

		} else {
			$result = $this->em->getRepository(RENTAL_ENTITY)->findByStatus(Rental::STATUS_LIVE, NULL, 30);
		}

		return $result;
	}

}
