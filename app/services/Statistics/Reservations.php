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
		$data = [];
		$countries = [];
		$periods = \Tools::getPeriods();
		$defaultRowData = ['iso' => '','today' => '','yesterday' => '','thisWeek' => '','lastWeek' => '','thisMonth' => '','lastMonth' => '','total' => ''];
		foreach($periods as $key => $value) {
			$qb = $this->reservationRepository->createQueryBuilder('e');
			$qb->select('pl.iso AS iso, count(e) AS c')
				->andWhere('e.created >= ?2')->setParameter('2', $value['from'])
				->andWhere('e.created < ?1')->setParameter('1', $value['to'])
				->innerJoin('e.rental', 'r')
				->innerJoin('r.address', 'a')
				->innerJoin('a.primaryLocation', 'pl')
				->groupBy('a.primaryLocation');

			$result = $qb->getQuery()->getResult();
			foreach($result as $row) {
				$countries[$row['iso']] = $row['iso'];
				$data[$row['iso']][$key] = $row['c'];
			}
		}

		foreach($countries as $country) {
			$data[$country] = array_merge($defaultRowData, $data[$country]);
			$data[$country]['iso'] = $country;
		}

		ksort($data);

		return Nette\ArrayHash::from($data);
	}
}
