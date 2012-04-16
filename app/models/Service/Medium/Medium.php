<?php

namespace Service\Medium;


class Medium extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\Medium\Medium';

	private static $knownTypes = array(
		'image/jpeg' => 'jpeg',
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
		'large' => array(
				'width'=>960,
				'height'=>600,
				'crop'=>FALSE
			),
		'medium' => array(
				'width'=>451, 
				'height'=>288, 
				'crop'=>TRUE
			),
		'small' => array(
				'width'=>235, 
				'height'=>145, 
				'crop'=>TRUE
			),
		'mini' => array(
				'width'=>100,
				'height'=>63,
				'crop'=>TRUE
			)
	);

	public static function createFromUrl($uri) {

		if (!$data = @file_get_contents($uri, 'r')) {
			throw new \Nette\UnexpectedValueException('File "' . $uri . '" not exists');
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
		$medium->uri = $uri;
		$medium->details = $medium->getFileDetails($file);
		$medium->sort = 1;
		$medium->save();

		if (preg_match("/image\//", $medium->details['mime'])) {
			$medium->saveImageFiles($file);
		} else {
			rename($file, $medium->getMediumDir() . '/original.' . $medium->details['extension']);
		}

		return $medium;

	}

	public function getThumbnail($size) {

		if (!$imgSize = $this::$imgSizes[$size]) {
			throw new \Nette\UnexpectedValueException('Image size "' . $size . '" not exists');
		}

		$uri = '/storage/';
		foreach ($this->getPathStructure() as $level) {
			$uri .= $level . '/';
		}
		$uri .=  $size . '.jpeg';

		return $uri;

	}

	public function delete() {

		$mediumDir = $this->getMediumDir();

		debug($mediumDir);

		foreach(glob($mediumDir . '/*') as $file) {
			if(is_dir($file))
				rrmdir($file);
			else
				unlink($file);
		}
		rmdir($mediumDir);

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
		$extension = $matches[1];
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
