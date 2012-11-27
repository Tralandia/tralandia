<?php

namespace Service\Medium;

use Nette\Http\FileUpload;
use Model\Medium\IMediumDecoratorFactory;

class MediumService extends \Service\BaseService {

	private static $knownTypes = array(
		'image/jpeg' => 'jpg',
		'image/png' => 'png',
		'image/gif' => 'gif',
		'application/pdf' => 'pdf'
	);

	private static $imgSizes = array(
		'original' => array(
				'width' => 0,
				'height' => 0,
				'crop' => FALSE
			),
		'full' => array(
				'width'=>451,
				'height'=>288,
				'crop'=>FALSE
			),
		'small' => array(
				'width'=>271, 
				'height'=>170, 
				'crop'=>TRUE
			)
	);

	public $mediumTypeRepositoryAccessor, $mediumTypeServiceFactory;
	
	public function injectRepositories(\Nette\DI\Container $dic)
	{
		$this->mediumTypeRepositoryAccessor = $dic->mediumTypeRepositoryAccessor;
	}

	public function injectDecorators(IMediumDecoratorFactory $mediumTypeServiceFactory)
	{
		$this->mediumTypeServiceFactory = $mediumTypeServiceFactory;
	}

	public function setContentFromUrl($uri) {

		if (!$data = @file_get_contents($uri, 'r')) {
			return FALSE;
			//throw new \Nette\UnexpectedValueException('File "' . $uri . '" does not exist.');
		}

		preg_match("/\.([^\.]+)$/", basename($uri), $matches);
		$extension = $matches[1];

		$file = TEMP_DIR . '/' . \Nette\Utils\Strings::random() . '.' . $extension;
		$handle = fopen($file, 'wb');
		fputs($handle, $data);
		fclose($handle);

		$this->save();
		$this->entity->oldUrl = $uri;
		$this->entity->details = $this->getFileDetails($file);
		$this->entity->sort = 1;
		$this->entity->setCreated();
		// debug($this);

		$mediumType = $this->mediumTypeRepositoryAccessor->get()->findOneByName($this->entity->details['mime']);
		if (!$mediumType) {
			$mediumType = $this->mediumTypeRepositoryAccessor->get()->createNew();
			$mediumType->name = $this->entity->details['mime'];
			$this->mediumTypeRepositoryAccessor->get()->persist($mediumType);
		}

		if (preg_match("/image\//", $this->entity->details['mime'])) {
			$this->saveImageFiles($file);
		} else {
			rename($file, $this->getMediumAbsolutepDir() . '/original.' . $this->entity->details['extension']);
		}
		$this->entity->type = $mediumType;

		return $this;

	}

	//@TODO - cela tato fcia je este todo :)
	public function getThumbnail($size) {

		if (!$imgSize = $this::$imgSizes[$size]) {
			throw new \Nette\UnexpectedValueException('Image size "' . $size . '" does not exist.');
		}

		$uri = '/storage'.$this->getMediumDir() . '/' . $size . '.jpg';

		return $uri;

	}

	public function delete($flush = true) {

		$mediumDir = $this->getMediumAbsolutepDir();

		foreach(glob($mediumDir . '/*') as $file) {
			if(is_dir($file)) {
				rmdir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($mediumDir);

		$pathStructure = $this->getPathStructure();
		foreach($pathStructure as $level) {
			if (count($pathStructure)<2) break;
			$pathStructure = array_splice($pathStructure, 0, -1);
			$parent = FILES_DIR . '/' . implode('/', $pathStructure);
			if (!count(glob($parent . '/*'))) rmdir($parent);
		}

		parent::delete($flush);

	}

	private function saveImageFiles($file) {

		$currentDir = $this->getMediumAbsolutepDir();
		@mkdir($currentDir, 0777, TRUE);

		foreach ($this::$imgSizes as $size => $options) {

			$newCopy = $currentDir . '/' . $size . '.' . $this->entity->details['extension'];

			$image = \Nette\Image::fromFile($file);
			if ($options['width'] && $options['height']) {
				if ($options['crop']) {
					$image->resizeCrop($options['width'], $options['height']);
				} else {
					$image->resize($options['width'], $options['height']);
				}
			}
			$image->save($newCopy);

		}

		unlink($file);

	}

	private function getMediumDir() {
		$dir = '/' . implode('/', $this->getPathStructure());

		return $dir;

	}

	private function getMediumAbsolutepDir() {
		$dir = FILES_DIR . '/' . implode('/', $this->getPathStructure());

		return $dir;

	}

	private function getFileDetails($file) {

		$details = array();

		$finfo = new \finfo(FILEINFO_MIME_TYPE);
		$details['mime'] = $finfo->file($file);
		$details['bytes'] = filesize($file);

		if (preg_match("/image\//", $details['mime'])) {
			$imageSize = getimagesize($file);
			$details['width'] = $imageSize[0];
			$details['heigth'] = $imageSize[1];
		}

		preg_match("/\.([^\.]+)$/", $file, $matches);
		$extension = isset($matches[1]) ? $matches[1] : NULL;
		$details['extension'] = (isset($this::$knownTypes[$details['mime']])?$this::$knownTypes[$details['mime']]:$extension);

		return $details;

	}

	private function getPathStructure() {
		return array(
			$this->entity->created->format('Y'),
			$this->entity->created->format('m'),
			$this->entity->created->format('d'),
			$this->entity->id
		);

	}
	
}
