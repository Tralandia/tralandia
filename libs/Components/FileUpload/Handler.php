<?php

namespace FileUpload;

class Handler extends \Nette\Object {

	public $paramName = 'file';

	public function getFilesInfo() {
		$upload = isset($_FILES[$this->paramName]) ?
			$_FILES[$this->paramName] : null;
		$info = array();
		if ($upload && is_array($upload['tmp_name'])) {
			// param_name is an array identifier like "files[]",
			// $_FILES is a multi-dimensional array:
			foreach ($upload['tmp_name'] as $index => $value) {
				$info[] = array(
					'tmp_name' => $upload['tmp_name'][$index],
					'name' => isset($_SERVER['HTTP_X_FILE_NAME']) ?
						$_SERVER['HTTP_X_FILE_NAME'] : $upload['name'][$index],
					'size' => isset($_SERVER['HTTP_X_FILE_SIZE']) ?
						$_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'][$index],
					'type' => isset($_SERVER['HTTP_X_FILE_TYPE']) ?
						$_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'][$index],
					$upload['error'][$index],
					'error' => $upload['error'][$index]
				);
			}
		} elseif ($upload || isset($_SERVER['HTTP_X_FILE_NAME'])) {
			// param_name is a single object identifier like "file",
			// $_FILES is a one-dimensional array:
			$info[] = array(
				'tmp_name' => isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
				'name' => isset($_SERVER['HTTP_X_FILE_NAME']) ?
					$_SERVER['HTTP_X_FILE_NAME'] : (isset($upload['name']) ? $upload['name'] : null),
				'size' => isset($_SERVER['HTTP_X_FILE_SIZE']) ?
					$_SERVER['HTTP_X_FILE_SIZE'] : (isset($upload['size']) ?
						$upload['size'] : null),
				'type' => isset($_SERVER['HTTP_X_FILE_TYPE']) ?
					$_SERVER['HTTP_X_FILE_TYPE'] : (isset($upload['type']) ?
						$upload['type'] : null),
				'error' => isset($upload['error']) ? $upload['error'] : null
			);
		}

		return $info;
	}

}