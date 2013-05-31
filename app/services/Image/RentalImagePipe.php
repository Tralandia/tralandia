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
	 * @var string
	 */
	private $staticDomain;


	/**
	 * @param string $wwwDir
	 * @param $imageDir
	 * @param $staticDomain
	 * @param \Nette\Http\Request $httpRequest
	 */
	public function __construct($wwwDir, $imageDir, $staticDomain, Request $httpRequest)
	{
		$this->wwwDir = $wwwDir;
		$this->imageDir = $imageDir;
		$this->staticDomain = $staticDomain;

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
		if($this->staticDomain) {
			$a = $this->staticDomain . str_replace('storage/', '', $this->imageDir);
		} else {
			$a = $this->baseUrl . $this->imageDir;
		}
		return $a . str_replace($this->wwwDir, '', $file);
	}

}
