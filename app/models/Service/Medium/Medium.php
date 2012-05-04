<?php

namespace Service\Medium;


class Medium extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\Medium\Medium';

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

	public static function createFromUrl($uri) {

		$medium = \Service\Medium\Medium::getByOldUrl($uri);
		if ($medium) {
			debug('Nasiel som medium, iba asociujem...');
			return $medium;
		}

		if (!$data = @file_get_contents($uri, 'r')) {
			return FALSE;
			//throw new \Nette\UnexpectedValueException('File "' . $uri . '" does not exist.');
		}

		preg_match("/\.([^\.]+)$/", basename($uri), $matches);
		$extension = $matches[1];

		$tmpCopy = TEMP_DIR . '/' . \Nette\Utils\Strings::random() . '.' . $extension;
		$handle = fopen($tmpCopy, 'wb');
		fputs($handle, $data);
		fclose($handle);

		return static::createFromFile($tmpCopy, $uri);

	}

	public static function createFromFile($file, $uri) {

		$medium = static::get();
		$medium->oldUrl = $uri;
		$medium->details = $medium->getFileDetails($file);
		$medium->sort = 1;
		$medium->save();

		$mediumType = \Service\Medium\Type::getByName($medium->details['mime']);
		if (!($mediumType instanceof \Service\Medium\Type)) {
			$mediumType = \Service\Medium\Type::get();
			$mediumType->name = $medium->details['mime'];
			$mediumType->save();
		}

		if (preg_match("/image\//", $medium->details['mime'])) {
			$medium->saveImageFiles($file);
		} else {
			rename($file, $medium->getMediumDir() . '/original.' . $medium->details['extension']);
		}
		$medium->type = $mediumType;
		$medium->save();

		return $medium;

	}

	//@TODO - cela tato fcia je este todo :)
	public function getThumbnail($size) {

		if (!$imgSize = $this::$imgSizes[$size]) {
			throw new \Nette\UnexpectedValueException('Image size "' . $size . '" does not exist.');
		}

		$uri = '/storage/';
		foreach ($this->getPathStructure() as $level) {
			$uri .= $level . '/';
		}
		$uri .=  $size . '.jpg'; //@todo - upravit aby to bolo KONSTANTA

		return $uri;

	}

	public function delete() {

		$mediumDir = $this->getMediumDir();

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

	}

	private function saveImageFiles($file) {

		$currentDir = $this->getMediumDir();

		foreach ($this::$imgSizes as $size => $options) {

			$newCopy = $currentDir . '/' . $size . '.' . $this->details['extension'];

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
		$dir = FILES_DIR;
		foreach ($this->getPathStructure() as $level) {
			$dir .= '/' . $level;
			if (!is_dir($dir)) mkdir($dir);
		}

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
		$details['extension'] = ($this::$knownTypes[$details['mime']]?:$extension);

		return $details;

	}

	private function getPathStructure() {

		return array(
			$this->created->format('Y'),
			$this->created->format('m'),
			$this->created->format('d'),
			$this->id
		);

	}
	
}
