<?php

namespace Service\Rental;

use Extras;
use Nette;
use Nette\Image;
use Service;


/**
 * ImageDecorator class
 *
 * @author Dávid Ďurika
 */
class ImageDecorator extends Service\BaseService {

	protected $storage;

	public function setStorage(Extras\FileStorage $storage) {
		$this->storage = $storage;
	}

	public function setContentFromFile($uri) {
		$image = Image::fromFile($uri);

		$path = $this->storage->saveImage($image);

		$this->entity->oldUrl = $uri;
		$this->entity->filePath = $path;
		return $path;
	}

}