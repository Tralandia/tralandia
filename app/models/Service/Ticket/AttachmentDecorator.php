<?php

namespace Service\Ticket;

use Extras;
use Nette;
use Nette\Image;
use Service;
use Nette\Http\FileUpload;


/**
 * AttachmentDecorator class
 *
 * @author Dávid Ďurika
 */
class AttachmentDecorator extends Service\BaseService {

	/**
	 * @var Extras\FileStorage
	 */
	protected $storage;

	/**
	 * @param \Extras\FileStorage $storage
	 */
	public function setStorage(Extras\FileStorage $storage) {
		$this->storage = $storage;
	}

	/**
	 * @param FileUpload $file
	 */
	public function setContentFromFile(FileUpload $file) {

		$path = $this->storage->upload($file);

		$this->entity->filePath = $path;
	}

}

interface IAttachmentDecoratorFactory {
	/**
	 * @param \Entity\Ticket\Attachment $entity
	 *
	 * @return AttachmentDecorator
	 */
	public function create(\Entity\Ticket\Attachment $entity);
}