<?php
namespace Image;

use Extras\FileStorage;
use Nette;
use Entity\Rental\Image;
use Nette\Utils\Finder;
use Nette\Utils\Strings;


/**
 * @author Dávid Ďurika
 */
class RentalImageStorage extends FileStorage
{
	const CROP = 'crop';
	const RESIZE = 'resize';
	const QUALITY = 90;

	const MIN_WIDTH = 500;
	const MIN_HEIGHT = 300;

	protected $sizeOptions = [
		Image::ORIGINAL => [
			'method' => self::RESIZE,
			'width' => 1200,
			'height' => NULL,
			'flag' => Nette\Image::SHRINK_ONLY
		],
		Image::MEDIUM => [
			'method' => self::CROP,
			'width' => 467,
			'height' => 276,
			'flag' => Nette\Image::FIT
		]
	];

	/**
	 * @param Nette\Image $image
	 *
	 * @return string
	 */
	public function saveImage(Nette\Image $image)
	{
		$path = $this->createFolder();

		$this->createMiniatures($path, $image);

		return $this->getRelativePath($path);
	}

	protected function createMiniatures($path, Nette\Image $image)
	{
		foreach ($this->sizeOptions as $key => $params) {
			$imageCopy = clone $image;
			if($params['method'] == self::CROP) {
				$imageCopy->resizeCrop($params['width'], $params['height'], $params['flag']);
			} else {
				$imageCopy->resize($params['width'], $params['height'], $params['flag']);
			}
			$imageCopy->save($path . DIRECTORY_SEPARATOR . $key . '.' . Image::EXTENSION, self::QUALITY);
		}
	}


	public function delete($imagePath)
	{
		foreach ($this->sizeOptions as $key => $params) {
			parent::delete($imagePath . DIRECTORY_SEPARATOR . $key . '.' . Image::EXTENSION);
		}
	}


	protected function createFolder()
	{
		$c = 0;
		do {
			$folders = implode(DIRECTORY_SEPARATOR, str_split(Strings::random(4), 2));
			$path = $this->filesDir . DIRECTORY_SEPARATOR . date('Y_m/d') . DIRECTORY_SEPARATOR .  $folders;
			$c++;
		} while (is_dir($path));
		
		d('Directories: '.$c);

		@mkdir($path, 0777, TRUE);

		return $path;
	}

}
