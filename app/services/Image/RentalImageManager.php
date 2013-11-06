<?php

namespace Image;

use Nette;
use Entity\Rental\Image;
use Tralandia\BaseDao;

class RentalImageManager {

	/**
	 * @var RentalImageStorage
	 */
	protected $storage;

	/**
	 * @var RentalImagePipe
	 */
	protected $pipe;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $rentalImageDao;


	/**
	 * @param \Tralandia\BaseDao $rentalImageDao
	 * @param RentalImageStorage $storage
	 * @param RentalImagePipe $pipe
	 *
	 */
	public function __construct(BaseDao $rentalImageDao, RentalImageStorage $storage, RentalImagePipe $pipe)
	{
		$this->storage = $storage;
		$this->pipe = $pipe;
		$this->rentalImageDao = $rentalImageDao;
	}

	/**
	 * @param \Nette\Image $image
	 *
	 * @return \Entity\Rental\Image
	 */
	public function save(Nette\Image $image)
	{
		$path = $this->storage->saveImage($image);

		/** @var $image \Entity\Rental\Image */
		$image = $this->rentalImageDao->createNew();
		$image->setFilePath($path);
		$this->rentalImageDao->save($image);
		return $image;
	}

	public function saveFromFile($uri) {
		$image = Nette\Image::fromFile($uri);
		return $this->save($image);
	}


	public function delete(\Entity\Rental\Image $image)
	{
		$this->storage->delete($image->getFilePath());

		$this->rentalImageDao->delete($image);
	}

	/**
	 * @param \Entity\Rental\Image $image
	 *
	 * @return string
	 */
	public function getImageRelativePath(Image $image){
		return $this->pipe->request($image);
	}

}
