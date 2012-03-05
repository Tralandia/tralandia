<?php

class DynamicPresenterFactory {
	
	private static $robotLoader;
	
	public static function enable(Nette\Loaders\RobotLoader $robotLoader) {
		self::$robotLoader = $robotLoader;
		spl_autoload_register('DynamicPresenterFactory::autoload');
	}
	
	public static function autoload($class) {
		if (strpos($class, "Presenter") && !array_key_exists($class, self::$robotLoader->getIndexedClasses())) {
			$class = explode('\\', $class);
			$class = array_pop($class);
			eval("namespace AdminModule { class $class extends AdminPresenter {} }");
		}
	}
}