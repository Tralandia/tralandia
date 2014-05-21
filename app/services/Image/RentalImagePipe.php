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


	protected $minDateForFullHd;


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
		$this->minDateForFullHd = new \DateTime('2014-03-20');
	}


	/**
	 * @param \Entity\Rental\Image $image
	 * @param string $size
	 * @return string
	 */
	public function request($image, $size = Image::MEDIUM)
	{
		if (!$image instanceof \Entity\Rental\Image && !$image instanceof \Tralandia\Rental\Image) {
			return NULL;
		}

		return $this->requestForPath($image->getFilePath(), $size);
	}


	/**
	 * @param $path
	 * @param string $size
	 * @param \DateTime|null $date
	 *
	 * @return string
	 */
	public function requestForPath($path, $size = Image::MEDIUM,\DateTime $date = NULL)
	{
		if($size == Image::FULL_HD && $date && $this->minDateForFullHd > $date) {
			$size = Image::MEDIUM;
		}

		$targetPath = $path . DIRECTORY_SEPARATOR . $size . '.' . Image::EXTENSION;

//		$defaultImage = '/default.jpg';
		$path = $this->publicPath($targetPath);
//		if ($this->isUrlExists($path) == FALSE) {
//			$path = $this->publicPath($defaultImage);
//		}
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

	/**
	 * @param string $url
	 * @return bool
	 */
	private function isUrlExists($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($code == 200){
			$status = true;
		}else{
			$status = false;
		}
		curl_close($ch);
		return $status;
	}

}
