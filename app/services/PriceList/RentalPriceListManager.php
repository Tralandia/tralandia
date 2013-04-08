<?php

namespace Image;

use Nette;

class RentalPriceListManager {

	protected $pricelistRepository;

	/**
	 * @var RentalImageStorage
	 */
	protected $storage;


	/**
	 * @param $pricelistRepository
	 * @param \Extras\FileStorage $storage
	 */
	public function __construct($pricelistRepository, \Extras\FileStorage $storage)
	{
		$this->pricelistRepository = $pricelistRepository;
		$this->storage = $storage;
	}


	/**
	 * @param $url
	 *
	 * @return \Entity\Rental\PriceList
	 */
	public function save($url)
	{
		$path = $this->storage->saveFromFile($url);

		$pricelistRepository = $this->pricelistRepository;

		/** @var $pricelist \Entity\Rental\PriceList */
		$pricelist = $pricelistRepository->createNew();

		$pricelist->setName($url);
		$pricelist->setOldUrl($url);
		$pricelist->setFilePath($path);

		$pricelistRepository->save($pricelist);

		return $pricelist;
	}

}
