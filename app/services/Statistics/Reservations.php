<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 8/6/13 12:38 PM
 */

namespace Statistics;


use Doctrine\ORM\EntityManager;
use Nette;
use DataSource\IDataSource;
use Nette\Utils\Paginator;


class Reservations implements IDataSource {

	/**
	 * @var \Repository\BaseRepository
	 */
	protected $reservationRepository;

	public function __construct(EntityManager $em)
	{
		$this->reservationRepository = $em->getRepository(RESERVATION_ENTITY);
	}


	/**
	 * @param $filter
	 * @param $order
	 * @param Paginator $paginator
	 *
	 * @return mixed
	 */
	public function getData($filter, $order, Paginator $paginator = NULL)
	{
		// TODO: Implement getData() method.
	}
}
