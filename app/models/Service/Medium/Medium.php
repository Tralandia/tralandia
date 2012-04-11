<?php

namespace Service\Medium;


class Medium extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\Medium\Medium';

	public static $knownTypes = array(
		'image/jpeg' => 'jpeg',
		'image/png' => 'png',
		'image/gif' => 'gif',
		'application/pdf' => 'pdf'
	);

	public static function createFromUrl($uri) {

		if (!$data = @file_get_contents($uri, 'r')) {
			throw new \Nette\UnexpectedValueException('File "' . $uri . '" not exists');
		}

		preg_match("/\.([^\.]+)$/", basename($uri), $matches);
		$extension = $matches[1];

		// making temp copy of the file
		$tmpCopy = TEMP_DIR . '/' . \Nette\Utils\Strings::random();
		$handle = fopen($tmpCopy, 'wb');
		fputs($handle, $data);
		fclose($handle);

		// saving to entity
		$medium = static::get();
		$medium->uri = $uri;
		$medium->details = $medium->getFileDetails($tmpCopy);
		$medium->sort = 1;
		$medium->save();

		$currentDir = $medium->getCurrentDir();
		rename($tmpCopy, $currentDir . '/original.' . ($medium::$knownTypes[$medium->details['mime']]?:$extension));

		return $medium;

	}

	private function getCurrentDir() {

		$dt = new \Extras\Types\DateTime();

		$dirLevels = array(
			$dt->format('Y'),
			$dt->format('m'),
			$dt->format('d'),
			$this->id
		);

		$dir = FILES_DIR;
		foreach ($dirLevels as $level) {
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

		if (current(explode('/', $details['mime']))=='image') {
			$imageSize = getimagesize($file);
			$details['width'] = $imageSize[0];
			$details['heigth'] = $imageSize[1];
		}

		return $details;

	}
	
}
