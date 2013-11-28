<?php

namespace AdminModule\Grids\Statistics;

use AdminModule\Components\AdminGridControl;
use Nette\Utils\Paginator;
use Tralandia\BaseDao;

class RentalEditGrid extends AdminGridControl
{

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $editLogDao;


	public function __construct(BaseDao $editLogDao) {
		$this->editLogDao = $editLogDao;
	}

	public function render() {
		$this->template->render();
	}

	public function createComponentGrid()
	{
		$this->showActions = FALSE;

		$grid = $this->getGrid();
		$grid->setRowPrimaryKey('iso');

		$grid->addColumn('iso', 'Country');
		$grid->addColumn('today', 'Today');
		$grid->addColumn('yesterday', 'Yesterday');
		$grid->addColumn('thisWeek', 'This week');
		$grid->addColumn('lastWeek', 'Last week');
		$grid->addColumn('thisMonth', 'This month');
		$grid->addColumn('lastMonth', 'Last month');
		$grid->addColumn('total', 'Total');

		return $grid;
	}


	public function getDataSource($filter, $order, Paginator $paginator = NULL)
	{
		$data = [];
		$defaultRowData = ['iso' => '','today' => '','yesterday' => '','thisWeek' => '','lastWeek' => '','thisMonth' => '','lastMonth' => '','total' => ''];
		$periods = \Tools::getPeriods();
		foreach($periods as $key => $value) {
			$qb = $this->editLogDao->createQueryBuilder('e');
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

		return \Nette\ArrayHash::from($data);
	}

}

interface IRentalEditGridFactory {

	/**
	 * @return RentalEditGrid
	 */
	public function create();
}
