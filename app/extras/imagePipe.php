<?php

namespace Extras;

use Kdyby;
use Entity\Rental\Image;
use Nette\Http\Request;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class ImagePipe extends Nette\Object
{

	const TEMP = 1;

	/**
	 * @var string
	 */
	private $imageDir;

	/**
	 * @var string
	 */
	private $wwwDir;

	/**
	 * @var string
	 */
	private $baseUrl;


	/**
	 * @param string $wwwDir
	 * @param $imageDir
	 * @param \Nette\Http\Request $httpRequest
	 */
	public function __construct($wwwDir, $imageDir, Request $httpRequest)
	{
		$this->wwwDir = $wwwDir;
		$this->imageDir = $imageDir;

		// base of public url
		$this->baseUrl = rtrim($httpRequest->url->baseUrl, '/');
	}


	/**
	 * @param \Entity\Rental\Image $image
	 * @param string $size
	 * @return string
	 */
	public function request(\Entity\Rental\Image $image, $size = Image::MEDIUM)
	{
		$targetPath = $image->getFilePath() . DIRECTORY_SEPARATOR . $size . '.' . Image::EXTENSION;

		return $this->publicPath($targetPath);
	}



	/**
	 * @param string $file
	 * @return string
	 */
	private function publicPath($file)
	{
		return $this->baseUrl . $this->imageDir . str_replace($this->wwwDir, '', $file);
	}

}