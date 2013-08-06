<?php

use Entity\Rental\Pricelist;
use Nette\Http\FileUpload;

class RentalPriceListManager {

	protected $pricelistRepository;

	/**
	 * @var \Extras\FileStorage
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
	 * @return PriceList
	 */
	public function save($url)
	{
		$path = $this->storage->saveFromFile($url);

		$pricelistRepository = $this->pricelistRepository;

		/** @var $pricelist PriceList */
		$pricelist = $pricelistRepository->createNew();

		$pricelist->setName($url);
		$pricelist->setOldUrl($url);
		$pricelist->setFilePath($path);

		$pricelistRepository->save($pricelist);

		return $pricelist;
	}


	/**
	 * @param FileUpload $file
	 *
	 * @return PriceList
	 */
	public function upload(FileUpload $file)
	{
		$path = $this->storage->upload($file);

		$pricelistRepository = $this->pricelistRepository;

		/** @var $pricelist PriceList */
		$pricelist = $pricelistRepository->createNew();

		$pricelist->setName($file->getName());
		$pricelist->setFilePath($path);
		$pricelist->setFileSize($file->getSize());

		$pricelistRepository->save($pricelist);

		return $pricelist;
	}

	public function delete(Pricelist $pricelist)
	{
		$this->storage->delete($pricelist->getFilePath());

		$pricelistRepository = $this->pricelistRepository;
		return $pricelistRepository->delete($pricelist);
	}


	public function getAbsolutePath(Pricelist $pricelist)
	{
		return $this->storage->getAbsolutePath($pricelist->getFilePath());
	}
}
