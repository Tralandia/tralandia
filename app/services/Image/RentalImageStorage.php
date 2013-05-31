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

	protected $paths = [
		Image::ORIGINAL => [
			'width' => 1200,
			'height' => NULL,
			'flag' => Nette\Image::SHRINK_ONLY
		],
		Image::MEDIUM => [
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
		foreach ($this->paths as $key => $params) {
			$imageCopy = clone $image;
			$imageCopy->resize($params['width'], $params['height'], $params['flag']);
			$imageCopy->save($path . DIRECTORY_SEPARATOR . $key . '.' . Image::EXTENSION);
		}
	}
	
	public function delete($imagePath)
	{
		foreach ($this->paths as $key => $params) {
			parent::delete($imagePath . DIRECTORY_SEPARATOR . $key . '.' . Image::EXTENSION);
		}
	}

	protected function createFolder() 
	{
		do {
			$folders = implode(DIRECTORY_SEPARATOR, str_split(Strings::random(4), 2));
			$path = $this->filesDir . DIRECTORY_SEPARATOR . date('Y_m/d') . DIRECTORY_SEPARATOR .  $folders;
		} while (is_dir($path));

		@mkdir($path, 0777, TRUE);

		return $path;
	}

}