<?php

namespace Image;

use Kdyby;
use Entity\Rental\Image;
use Nette\Http\Request;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class RentalImagePipe extends Nette\Object implements IImagePipe
{

	const TEMP = 1;

	/**
	 * @var string
	 */
	private $baseUrl;


	/**
	 * @param $imagePath
	 * @param $staticDomain
	 * @param Request $httpRequest
	 */
	public function __construct($imagePath, $staticDomain, Request $httpRequest)
	{
		if($staticDomain) {
			$this->baseUrl = $staticDomain . $imagePath;
		} else {
			$this->baseUrl = rtrim($httpRequest->url->baseUrl, '/') . $imagePath;
		}
	}


	/**
	 * @param \Entity\Rental\Image $image
	 * @param string $size
	 * @return string
	 */
	public function request($image, $size = Image::MEDIUM)
	{
		if (!$image instanceof \Entity\Rental\Image) {
			return NULL;
		}
		$targetPath = $image->getFilePath() . DIRECTORY_SEPARATOR . $size . '.' . Image::EXTENSION;
		$defaultImage = '/default.jpg';
		$path = $this->publicPath($targetPath);
		file_exists($path) ? : $path = $this->publicPath($defaultImage);
		return $path;
	}


	/**
	 * @param string $file
	 * @return string
	 */
	private function publicPath($file)
	{
		return $this->baseUrl . $file;
	}

}
