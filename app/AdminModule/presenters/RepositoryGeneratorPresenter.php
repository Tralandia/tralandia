<?php

namespace AdminModule;

use Nette\Reflection;
use Nette\Utils\PhpGenerator;
use Nette\Utils\Strings;
use Nette\Utils\Finder;

/**
 * RepositoryGeneratorPresenter class
 *
 * @author Dávid Ďurika
 */
class RepositoryGeneratorPresenter extends BasePresenter {

	// public function renderDefault() {
	// 	$dirName = APP_DIR . '/models/Repository/';
	// 	$entityDir = APP_DIR . '/models/Entity/';
	// 	$configList = array();
	// 	foreach (Finder::findFiles('*.php')->from($entityDir) as $key => $file) {
	// 		list($x, $entityNameTemp) = explode('/models/', $key, 2);
	// 		$entityNameTemp = str_replace(array('/', '.php'), array('\\', ''), $entityNameTemp);

	// 		$entityNameArray = explode("\\", $entityNameTemp);
	// 		array_shift($entityNameArray);
	// 		$serviceName = array_unique($entityNameArray);
	// 		$serviceName = lcfirst(implode('', $serviceName) . 'Repository');

	// 		$configList[$serviceName] = array(
	// 			'class' => str_replace('Entity', 'Repository', $entityNameTemp) . 'Repository',
	// 			'facotry' => "@model::getRepository('$entityNameTemp')",
	// 		);

	// 		$className = array_pop($entityNameArray) . 'Repository';
	// 		array_unshift($entityNameArray, 'Repository');
	// 		$namespace = implode('\\', $entityNameArray);
			
	// 		if(class_exists('\\'.$namespace.'\\'.$className)) continue;

	// 		$content = "<?php\nnamespace $namespace;\nclass $className extends \Repository\BaseRepository {\n}";

	// 		$filename = $dirName . str_replace(array('Repository\\', '\\'), array('', '/'), $namespace) . '/' . $className . '.php';
	// 		d($filename, $content);
	// 		@mkdir(dirname($filename), 0777, true);
	// 		file_put_contents($filename, $content);

	// 	}
	// 	//d($configList);
	// 	$neon = new \Nette\Utils\Neon;
	// 	$neonOutput = $neon->encode($configList, $neon::BLOCK);
	// 	$this->template->neon = $neonOutput;
	// }


	public function renderDefault() {
		$dirName = APP_DIR . '/models/Repository/';
		$entityDir = APP_DIR . '/models/Entity/';
		$configList = array();
		foreach (Finder::findFiles('*.php')->from($entityDir) as $key => $file) {
			list($x, $entityNameTemp) = explode('/models/', $key, 2);
			$entityNameTemp = str_replace(array('/', '.php'), array('\\', ''), $entityNameTemp);

			$entityNameArray = explode("\\", $entityNameTemp);
			array_shift($entityNameArray);
			$entityNameArray = array_unique($entityNameArray);
			$serviceName = lcfirst(implode('', $entityNameArray) . 'ServiceFactory');
			$entityFactoryName = lcfirst(implode('', $entityNameArray) . 'EntityFactory');

			$configList[$serviceName] = "Extras\Models\Service\ServiceFactory(@model, 'Service\BaseService', @$entityFactoryName)";

		}
		//d($configList);
		$neon = new \Nette\Utils\Neon;
		$neonOutput = $neon->encode($configList, $neon::BLOCK);
		$this->template->neon = $neonOutput;
	}
}