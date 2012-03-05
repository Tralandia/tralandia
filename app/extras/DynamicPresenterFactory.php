<?php

class DynamicPresenterFactory {
	
	private static $robotLoader;
	
	public static function enable(Nette\Loaders\RobotLoader $robotLoader) {
		self::$robotLoader = $robotLoader;
		spl_autoload_register('DynamicPresenterFactory::autoload');
	}
	
	public static function autoload($class) {
		debug(self::$robotLoader);
		
		
		$loader = self::$robotLoader;
		if (strpos($class, "Presenter") && !array_key_exists($class, $loader->getIndexedClasses())) {
			$class = explode('\\', $class);
			$class = array_pop($class);
			eval("namespace AdminModule { class $class extends AdminPresenter {} }");
		}
	}
}