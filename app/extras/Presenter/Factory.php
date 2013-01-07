<?php

namespace Extras\Presenter;

use Nette;

class Factory extends Nette\Application\PresenterFactory {

	/** @var string */
	private $tempDir;

	/**
	 * @param  string
	 */
	public function __construct($baseDir, $tempDir, Nette\DI\Container $container)
	{
		parent::__construct($baseDir, $container);
		$this->tempDir = $tempDir;
	}

	/**
	 * @param string
	 * @param string
	 * @param  Nette\DI\Container
	 * @return Factory
	 */
	public static function factory($baseDir, $tempDir, Nette\DI\Container $context)
	{
		return new self($baseDir, $tempDir, $context);
	}

	/**
	 * @param  string  presenter name
	 * @return string  class name
	 * @throws Nette\Application\InvalidPresenterException
	 */
	public function getPresenterClass(& $name)
	{
		if (isset($this->cache[$name])) {
			list($class, $name) = $this->cache[$name];
			return $class;
		}

		if (!is_string($name) || !Nette\Utils\Strings::match($name, '#^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x7f-\xff:]*\z#')) {
			throw new Nette\Application\InvalidPresenterException("Presenter name must be alphanumeric string, '$name' is invalid.");
		}

		$class = $this->formatPresenterClass($name);

		if (!class_exists($class)) {
			// internal autoloading
			$file = $this->formatPresenterFile($name);
			if (is_file($file) && is_readable($file)) {
				Nette\Utils\LimitedScope::load($file, TRUE);
			}

			if (!class_exists($class) && strpos($class, 'AdminModule\\') === 0) {
				$file = $this->formatTempPresenterFile($name);
				if (!file_exists($file)) {
					$this->generateTempPresenter($name, $file);
				}
				if (is_file($file) && is_readable($file)) {
					Nette\Utils\LimitedScope::load($file, TRUE);
				}
			}

			if (!class_exists($class)) {
				throw new Nette\Application\InvalidPresenterException("Cannot load presenter '$name', class '$class' was not found in '$file'.");
			}
		}

		$reflection = new Nette\Reflection\ClassType($class);
		$class = $reflection->getName();

		if (!$reflection->implementsInterface('Nette\Application\IPresenter')) {
			throw new Nette\Application\InvalidPresenterException("Cannot load presenter '$name', class '$class' is not Nette\\Application\\IPresenter implementor.");
		}

		if ($reflection->isAbstract()) {
			throw new Nette\Application\InvalidPresenterException("Cannot load presenter '$name', class '$class' is abstract.");
		}

		// canonicalize presenter name
		$realName = $this->unformatPresenterClass($class);
		if ($name !== $realName) {
			if ($this->caseSensitive) {
				throw new Nette\Application\InvalidPresenterException("Cannot load presenter '$name', case mismatch. Real name is '$realName'.");
			} else {
				$this->cache[$name] = array($class, $realName);
				$name = $realName;
			}
		} else {
			$this->cache[$name] = array($class, $realName);
		}

		return $class;
	}

	/**
	 * Vygeneruje temp prezenter
	 * @param string
	 * @param string
	 */
	public static function generateTempPresenter($presenter, $file)
	{
		$parts = explode(':', $presenter);
		$name = end($parts) . 'Presenter';

		$content = "<?php\nnamespace AdminModule;\nclass $name extends AdminPresenter {\n}";
		@mkdir(dirname($file), 0777, true);
		file_put_contents($file, $content);
	}

	/**
	 * Formats temp presenter class file name.
	 * @param  string
	 * @return string
	 */
	public function formatTempPresenterFile($presenter)
	{
		$parts = explode(':', $presenter);
		return $this->tempDir . '/' . end($parts) . 'Presenter.php';
	}
}