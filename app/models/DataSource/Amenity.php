<?php

namespace DataSource;


use Nette\Utils\Paginator;

class Amenity extends BaseDataSource {

	private $amenityRepository;

	/**
	 * @var \ResultSorter
	 */
	private $resultSorter;


	public function __construct($amenityRepository, \ResultSorter $resultSorter)
	{
		$this->amenityRepository = $amenityRepository;
		$this->resultSorter = $resultSorter;
	}


	public function getData($filter, $order, Paginator $paginator = NULL)
	{
		$result = $this->amenityRepository->findAll();
		// najprv zoradim podla abecedy
		$orderedAmenities = $this->resultSorter->translateAndSortResult($result, function($v) {return $v->getName();});

		// a potom podla important
		$important = [];
		$notImportant = [];
		foreach ($orderedAmenities as $key => $entity) {
			$id = $entity->getId();
			if ($entity->important) {
				$important[$id] = $entity;
			} else {
				$notImportant[$id] = $entity;
			}
		}

		$amenities = array_merge($important, $notImportant);

		return $amenities;
	}

	public function getDataCount()
	{

	}
}
