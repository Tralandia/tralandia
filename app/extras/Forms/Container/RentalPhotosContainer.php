<?php

namespace Extras\Forms\Container;

use Extras\Forms\Control\MfuControl;
use Image\RentalImageManager;

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


	public function __construct(\Entity\Rental\Rental $rental = NULL, RentalImageManager $imageManager)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->imageManager = $imageManager;

		$this['upload'] = $upload = new MfuControl();
		$upload->allowMultiple()->onUpload[] = $this->processUpload;

		$this->addHidden('sort');
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
		if(!$this->rental) return [];
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
				$image = $this->imageManager->save($file->toImage());
				$payload[] = [
					'id' => $image->getId(),
					'path' => $this->imageManager->getImageRelativePath($image),
				];
			}
		}

		if ($this->getForm()->getPresenter()->isAjax() && $payload) {
			$this->getForm()->getPresenter()->sendJson($payload);
		}
	}


}
