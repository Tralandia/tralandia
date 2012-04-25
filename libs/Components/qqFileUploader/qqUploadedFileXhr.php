<?php

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {

	/**
	* Save the file to the specified path
	* @return boolean TRUE on success
	*/
	function save() {
		$input = fopen("php://input", "r");
		$temp = tmpfile();
		$file = tempnam(sys_get_temp_dir(), "qqfile");
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);

		if ($realSize != $this->getSize()) {
			return false;
		}

		$target = fopen($file, "w");
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);

		return array(
			'name' => $this->getName(),
			'type' => Nette\Utils\MimeTypeDetector::fromFile($file),
			'size' => $this->getSize(),
			'tmp_name' => $file,
			'error' => UPLOAD_ERR_OK
		);
	}

	function getName() {
		return $_GET['qqfile'];
	}

	function getSize() {
		if (isset($_SERVER["CONTENT_LENGTH"])){
			return (int)$_SERVER["CONTENT_LENGTH"];
		} else {
			throw new Exception('Getting content length is not supported.');
		}
	}
}