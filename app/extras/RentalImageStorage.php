<?php
namespace Extras;

use Nette;
use Nette\Http\FileUpload;
use Nette\Utils\Finder;
use Nette\Utils\Strings;


/**
 * @author Dávid Ďurika
 */
class RentalImageStorage extends FileStorage
{

	/**
	 * @param Nette\Image $image
	 * @param string $filename
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
		$imageFull = clone $image;

		$image->resize(1200, NULL);
		$image->save($path . DIRECTORY_SEPARATOR . 'original.jpeg');

		$imageFull->resizeCrop(467, 276);
		$imageFull->save($path . DIRECTORY_SEPARATOR . 'full.jpeg');
	}


	protected function createFolder() 
	{
		do {
			$folders = implode(DIRECTORY_SEPARATOR, str_split(Strings::random(6), 2));
			$path = $this->filesDir . DIRECTORY_SEPARATOR . date('Y_m/d') . DIRECTORY_SEPARATOR .  $folders;
		} while (is_dir($path));

		@mkdir($path, 0777, TRUE);

		return $path;
	}

}