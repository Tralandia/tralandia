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

	private $fakeImages = ['61/13408040347241', '62/13481447622502', '88/13492616958914', '20/13493474250862', '03/13497077840824', '34/13497124592268', '06/13481350326739', '44/13487502697093', '04/13498067239461', '86/13498564035416', '02/13504600165413'];

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

	public function requestFake()
	{
		return 'http://www.tralandia.sk/u/' . $this->fakeImages[array_rand($this->fakeImages)] . '.jpg';
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