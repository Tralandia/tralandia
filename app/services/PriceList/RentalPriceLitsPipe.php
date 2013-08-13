<?php

namespace Image;

use Entity\Rental\Pricelist;
use Kdyby;
use Entity\Rental\Image;
use Nette\Http\Request;
use Nette;


class RentalPriceListPipe extends Nette\Object
{

	const TEMP = 1;

	/**
	 * @var string
	 */
	private $baseUrl;


	/**
	 * @param $path
	 * @param $staticDomain
	 * @param Request $httpRequest
	 */
	public function __construct($path, $staticDomain, Request $httpRequest)
	{
		if($staticDomain) {
			$this->baseUrl = $staticDomain . $path;
		} else {
			$this->baseUrl = rtrim($httpRequest->url->baseUrl, '/') . $path;
		}
	}


	public function request($pricelist)
	{
		if (!$pricelist instanceof Pricelist) {
			return NULL;
		}
		$targetPath = $pricelist->getFilePath();

		return $this->publicPath($targetPath);
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
