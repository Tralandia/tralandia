<?php

namespace AdminModule\Grids\Statistics;

use AdminModule\Components\AdminGridControl;
use Nette\ArrayHash;
use Nette\Utils\Arrays;
use Nette\Utils\Paginator;
use Tralandia\BaseDao;
use Tralandia\Location\Countries;

class UnsubscribedGrid extends AdminGridControl {

	/**
	 * @var \Statistics\Registrations
	 */
	protected $dataSource;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $potentialMemberDao;

	/**
	 * @var \Tralandia\Location\Countries
	 */
	private $countries;


	public function __construct(BaseDao $potentialMemberDao, Countries $countries) {
		$this->potentialMemberDao = $potentialMemberDao;
		$this->countries = $countries;
	}

	public function render() {
		$this->template->hideActions = false;
		$this->template->render();
	}

	public function createComponentGrid()
	{
		$this->showActions = false;

		$grid = $this->getGrid();
		$grid->setRowPrimaryKey('key');

		$grid->addColumn('key', 'Country');
		$grid->addColumn('pot', 'pot.');
		$grid->addColumn('unsubscribed');

		return $grid;
	}


	public function getDataSource($filter, $order, Paginator $paginator = NULL)
	{
		$countries = $this->countries->findAll();
		$countries = \Tools::arrayMap($countries, function($key, $value) {return $value->getId();}, NULL);


		$qb = $this->potentialMemberDao->createQueryBuilder('pm');

		$qb->select('l.id locationId', 'COUNT(pm.id) as c')
			->innerJoin('pm.primaryLocation', 'l')
			->andWhere($qb->expr()->eq('pm.unsubscribed', TRUE))
			->groupBy('l.id');

		$unsubscribedCount = $qb->getQuery()->getArrayResult();

		$potQb = $this->potentialMemberDao->createQueryBuilder('pm');
		$potQb->select('l.id locationId', 'COUNT(pm.id) as c')
			->innerJoin('pm.primaryLocation', 'l')
			->andWhere($qb->expr()->eq('pm.unsubscribed', ':unsubscribed'))->setParameter('unsubscribed', FALSE)
			->groupBy('l.id');

		$potCount = $potQb->getQuery()->getArrayResult();

		foreach($unsubscribedCount as $key => $row) {
			Arrays::renameKey($unsubscribedCount, $key, $row['locationId']);
		}

		$finalResults = ['total' => ['key' => 'total']];
		foreach($potCount as $row) {
			$count = $row['c'];
			$locationId = $row['locationId'];
			$iso = $countries[$locationId]->getIso();
			$finalResults[$iso]['key'] = $iso;
			$finalResults[$iso]['pot'] = $count;
			$finalResults[$iso]['unsubscribed'] = $unsubscribedCount[$locationId]['c'];
			$finalResults['total']['pot'] += $count;
			$finalResults['total']['unsubscribed'] += $unsubscribedCount[$locationId]['c'];
		}

		$total = $finalResults['total'];
		unset($finalResults['total']);
		ksort($finalResults);
		$finalResults = array('total' => $total) + $finalResults;

		$finalResults = ArrayHash::from($finalResults);
		return $finalResults;
	}

}

interface IUnsubscribedGridFactory {

	/**
	 * @return UnsubscribedGrid
	 */
	public function create();
}
