<?php

namespace Extras;

use Nette\Config,
	Nette\Utils\Finder;

class PresenterGenerator extends \Nette\Object {

	public static $dirName = '/presenters';

	public static function generate() {
		# @todo volanie konstanty TEMP_DIR porusuje DI !
		$dirName = TEMP_DIR . self::$dirName;
		//var_dump($dirName);
		if(is_dir($dirName)){
			rrmdir($dirName);
		}

		@mkdir($dirName, 0777, true);

		foreach (Finder::findFiles('*.neon')->in(APP_DIR . '/configs/presenters') as $key => $file) {
			$filename = $file->getFilename();
			$name = substr($filename, 0, strrpos($filename, '.'));
			if($name == 'baseConfig') continue;
			$presenterName = ucfirst($name) . 'Presenter';
			if(class_exists('\AdminModule\\'.$presenterName)) continue;
			$extension = strtolower(substr($filename, strrpos($filename, '.') + 1));

			$content = "<?php\nnamespace AdminModule;\nclass $presenterName extends AdminPresenter {\n}";

			$handle = fopen($dirName . '/' . $presenterName . '.php', 'c');
			fwrite($handle, $content);
			fclose($handle);
		}

	}

}