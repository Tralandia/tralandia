<?php

namespace Image;

use Nette;
use Entity\Rental\Image;

class RentalImageManager {

	protected $rentalImageRepositoryAccessor;

	/**
	 * @var RentalImageStorage
	 */
	protected $storage;

	/**
	 * @var RentalImagePipe
	 */
	protected $pipe;

	/**
	 * @param $rentalImageRepositoryAccessor
	 * @param RentalImageStorage $storage
	 * @param RentalImagePipe $pipe
	 */
	public function __construct($rentalImageRepositoryAccessor, RentalImageStorage $storage, RentalImagePipe $pipe)
	{
		$this->rentalImageRepositoryAccessor = $rentalImageRepositoryAccessor;
		$this->storage = $storage;
		$this->pipe = $pipe;
	}

	/**
	 * @param \Nette\Image $image
	 *
	 * @return \Entity\Rental\Image
	 */
	public function save(Nette\Image $image)
	{
		$path = $this->storage->saveImage($image);

		$imageRepository = $this->rentalImageRepositoryAccessor->get();

		/** @var $image \Entity\Rental\Image */
		$image = $imageRepository->createNew();
		$image->setFilePath($path);
		$imageRepository->save($image);
		return $image;
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