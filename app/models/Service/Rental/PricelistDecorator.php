<?php

namespace Service\Rental;

use Extras;
use Nette;
use Nette\Image;
use Service;


/**
 * PricelistDecorator class
 *
 * @author Dávid Ďurika
 */
class PricelistDecorator extends Service\BaseService {

	protected $storage;

	public function setStorage(Extras\FileStorage $storage) {
		$this->storage = $storage;
	}

	public function setContentFromFile($url) {

		$path = $this->storage->saveFromFile($url);

		$this->entity->oldUrl = $url;
		$this->entity->filePath = $path;
	}

}