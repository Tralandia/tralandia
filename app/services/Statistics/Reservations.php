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
		$defaultRowData = ['iso' => '','today' => '','yesterday' => '','thisWeek' => '','lastWeek' => '','thisMonth' => '','lastMonth' => '','total' => ''];
		$periods = \Tools::getPeriods();
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
				$data[$row['iso']][$key] = $row['c'];
			}
		}

		$total = $defaultRowData;
		$total['iso'] = 'total';
		foreach($data as $country => $rowData) {
			$data[$country] = array_merge($defaultRowData, $data[$country]);
			$data[$country]['iso'] = $country;
			foreach($data[$country] as $key => $cell) {
				if($key == 'iso') continue;
				$total[$key] += $cell;
			}
		}

		ksort($data);

		$data = ['total' => $total] + $data;

		return Nette\ArrayHash::from($data);
	}
}
