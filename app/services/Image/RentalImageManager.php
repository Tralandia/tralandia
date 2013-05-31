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

	public function saveFromFile($uri) {
		$image = Nette\Image::fromFile($uri);
		return $this->save($image);
	}

	/**
	 * @return TRUE|FALSE
	 */
	public function delete(\Entity\Rental\Image $image)
	{
		$this->storage->delete($image->getFilePath());

		$imageRepository = $this->rentalImageRepositoryAccessor->get();
		return $imageRepository->delete($image);
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
