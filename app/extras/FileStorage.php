<?php
namespace Extras;

use Nette;
use Nette\Http\FileUpload;
use Nette\Utils\Finder;
use Nette\Utils\Strings;



/**
 * @author Filip Procházka <filip.prochazka@kdyby.org>
 */
class FileStorage extends Nette\Object
{

	/**
	 * @var string
	 */
	private $filesDir;



	/**
	 * @param string $dir
	 */
	public function __construct($dir)
	{
		if (!is_dir($dir)) {
			umask(0);
			mkdir($dir, 0777);
		}

		$this->filesDir = $dir;
	}



	/**
	 * @param FileUpload $file
	 * @return string
	 * @throws \Nette\InvalidArgumentException
	 */
	public function upload(FileUpload $file)
	{
		if (!$file->isOk()) {
			throw new Nette\InvalidArgumentException;
		}

		$filename = $file->getSanitizedName();
		$path = $this->generateFilePath($filename);

		$file->move($path);
		return $this->getRelativePath($path);
	}


	/**
	 * @param string $content
	 * @param string $filename
	 * @return string
	 */
	public function save($content, $filename)
	{
		$path = $this->generateFilePath($filename);

		file_put_contents($path, $content);
		return $this->getRelativePath($path);
	}


	/**
	 * @param $filePath
	 *
	 * @return bool
	 * @throws \Nette\FileNotFoundException
	 */
	public function delete($filePath)
	{
		$filePath = $this->filesDir . $filePath;
		if (!file_exists($filePath)) {
			throw new Nette\FileNotFoundException("$filePath");
		}
		return unlink($filePath);
	}

	public function saveFromFile($filePath)
	{
		// if (strlen(get_file_contents($filePath) == 0) {
		if (file_exists($filePath)) {
			throw new Nette\FileNotFoundException("$filePath");
		}

		$content = file_get_contents($filePath);
		$extension = get_file_extension($filePath);
		return $this->save($content, time() . '.' . $extension);
	}

	/**
	 * @return string
	 */
	public function getFilesDir()
	{
		return $this->filesDir;
	}



	/**
	 * @param $param
	 * @throws FileNotFoundException
	 * @return string
	 */
	public function find($param)
	{
		foreach (Finder::findFiles($param)->from($this->filesDir) as $file) {
			/** @var \SplFileInfo $file */
			return $file->getPathname();
		}

		throw new FileNotFoundException("File $param not found.");
	}

	public function getRelativePath($path)
	{
		return str_replace($this->filesDir, '', $path);
	}

	public function getAbsolutePath($path)
	{
		return $this->filesDir . $this->getRelativePath($path);
	}

	protected function generateFilePath($filename)
	{
		do {
			$randString = Strings::random(10);
			$name = substr($randString, 6) . '.' . $filename;
			$folders = str_split(substr($randString, 0, 6), 2);
			$path = $this->filesDir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $folders) . DIRECTORY_SEPARATOR . $name;
		} while (file_exists($path));

		@mkdir(dirname($path), 0777, TRUE);

		return $path;
	}

}



/**
 * @author Filip Procházka <filip.prochazka@kdyby.org>
 */
class FileNotFoundException extends \RuntimeException
{

}
