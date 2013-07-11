<?php

namespace Extras\Forms\Container;

use Extras\Forms\Control\MfuControl;
use Image\RentalImageManager;
use Image\RentalImageStorage;

class RentalPhotosContainer extends BaseContainer
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Image\RentalImageManager
	 */
	protected $imageManager;

	protected $imageRepository;


	/**
	 * @param \Entity\Rental\Rental|NULL $rental
	 * @param \Image\RentalImageManager $imageManager
	 * @param $imageRepository
	 */
	public function __construct(\Entity\Rental\Rental $rental = NULL, RentalImageManager $imageManager, $imageRepository)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->imageManager = $imageManager;
		$this->imageRepository = $imageRepository;

		$this['upload'] = $upload = new MfuControl();
		$upload->allowMultiple()->onUpload[] = $this->processUpload;

		$sort = [];
		if($rental) {
			$images = $rental->getImages();
			foreach($images as $image) {
				$sort[] = $image->getId();
			}
		}

		$this->addHidden('sort')->setDefaultValue(implode(',', $sort));
	}

	public function getMainControl()
	{
		return $this['upload'];
	}

	/**
	 * @return array|null
	 */
	public function getImages()
	{
		$images = $this->getFormattedValues()->images;
		if(!count($images)) return [];
		return $images;
	}


	/**
	 * @param \Extras\Forms\Control\MfuControl $upload
	 * @param array|\Nette\Http\FileUpload[] $files
	 */
	public function processUpload(MfuControl $upload, array $files)
	{
		$payload = [];
		$minWidth = RentalImageStorage::MIN_WIDTH;
		$minHeight = RentalImageStorage::MIN_HEIGHT;
		foreach($files as $file) {
			if($file->isOk()) {
				if($file->isImage()) {
					$image = $file->toImage();
					if($image->getWidth() <= $minWidth || $image->getHeight() <= $minHeight) {
						$payload[] = [
							'error' => 'smallImage',
						];
					} else {
						$imageEntity = $this->imageManager->save($file->toImage());
						$payload[] = [
							'id' => $imageEntity->getId(),
							'path' => $this->imageManager->getImageRelativePath($imageEntity),
						];
					}
				} else {
					$payload[] = [
						'error' => 'wrongFile',
					];
				}
			} else {
				$payload[] = [
					'error' => 'uploadFail',
				];
			}
		}

		if ($this->getForm()->getPresenter()->isAjax() && $payload) {
			$this->getForm()->getPresenter()->sendJson($payload);
		}
	}

	public function getFormattedValues($asArray = FALSE)
	{
		$values = $asArray ? array() : new \Nette\ArrayHash;
		$sort = $this['sort']->getValue();
		$values['sort'] = array_filter(explode(',', $sort));

		$imagesTemp = [];
		if(count($values['sort'])) {
			$images = $this->imageRepository->findById($values['sort']);

			$imagesTemp = array_flip($values['sort']);
			foreach($images as $image) {
				$imagesTemp[$image->getId()] = $image;
			}
		}
		$values['images'] = $imagesTemp;

		return $values;
	}



}
