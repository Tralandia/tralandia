<?php

namespace FrontModule;


use dibi;
use Nette\ArrayHash;

class DestinationPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Service\Rental\IRentalSearchServiceFactory
	 */
	protected $rentalSearchFactory;

	/**
	 * @autowire
	 * @var \SearchGenerator\OptionGenerator
	 */
	protected $searchOptionGenerator;

	public function __construct()
	{

	}

	public function renderDefault()
	{
		$this->template->getLinks = $this->getLocationsLinks;
	}


	public function getLocationsLinks()
	{
//		$search = $this->rentalSearchFactory->create($this->environment->primaryLocation);

//		lt('search', 'lt-temp');
//		/** @var $topLocation \SearchGenerator\TopLocations */
//		$topLocation = $this->getContext()->getByType('\SearchGenerator\TopLocations');
//		$top = $topLocation->getResults(NULL, $search);
//		lt('search', 'lt-temp');

		/** @var $db \DibiConnection */
		$db = $this->getContext()->getService('connection');
		$result = $db->query('select l.id, l.name_id, count(l.id) as c from location l
inner join contact_address a on a.locality_id = l.id
where l.parent_id = %i
group by l.id', $this->primaryLocation->getId());
		//$t = dibi::$sql;

		$links = [];
		foreach ($result as $row) {
			$links[$row->id] = [
				'entity' => $row->id,
				'name' => $this->translate($row->name_id),
				'count' => $row->c,
			];
		}


		$collator = $this->environment->getLocale()->getCollator();
		$collator->asortByKey($links, 'name');

		$links = ArrayHash::from($links);

		return array_chunk((array) $links, ceil(count($links)/3));
	}

}
