<?php

namespace Extras\Forms\Container;

use Extras\Forms\Control\MfuControl;
use Extras\ImagePipe;
use Extras\RentalImageStorage;

class RentalPhotosContainer extends BaseContainer
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	protected $imageRepository;

	/**
	 * @var \Extras\RentalImageStorage
	 */
	protected $imageStorage;

	/**
	 * @var \Extras\ImagePipe
	 */
	protected $imagePipe;

	public function __construct(\Entity\Rental\Rental $rental, $imageRepository, RentalImageStorage $imageStorage,
								ImagePipe $imagePipe)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->imageRepository = $imageRepository;
		$this->imageStorage = $imageStorage;
		$this->imagePipe = $imagePipe;

		$this['upload'] = $upload = new MfuControl();
		$upload->allowMultiple()->onUpload[] = $this->processUpload;

		$this->addHidden('sort');
	}

	public function getMainControl()
	{
		return $this['upload'];
	}

	public function getImages()
	{
		return $this->rental->getImages();
	}

	/**
	 * @param \Extras\Forms\Control\MfuControl $upload
	 * @param array|\Nette\Http\FileUpload[] $files
	 */
	public function processUpload(MfuControl $upload, array $files)
	{
		$payload = [];
		foreach($files as $file) {
			if($file->isOk() && $file->isImage()) {
				$path = $this->imageStorage->saveImage($file->toImage());

				/** @var $image \Entity\Rental\Image */
				$image = $this->imageRepository->createNew();
				$image->setFilePath($path);
				$this->imageRepository->save($image);

				$payload[] = [
					'id' => $image->getId(),
					'path' => $this->imagePipe->request($image),
				];
			}
		}

		if ($this->getForm()->getPresenter()->isAjax() && $payload) {
			$this->getForm()->getPresenter()->sendJson($payload);
		}
	}


}
