<?php
namespace Extras;

/**
 * Helpers class
 *
 * @author Dávid Ďurika
 */
class Helpers {

	public $rentalImageDir;

	public function loader($helper)
	{
		if (method_exists($this, $helper)) {
			return callback($this, $helper);
		}
	}


	public function rentalImageSrc($image, $size = 'thumbnail')
	{
		return $this->rentalImageDir . $image->filePath . DIRECTORY_SEPARATOR . $size . '.jpeg';
	}
}