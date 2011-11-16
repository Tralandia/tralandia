<?php

class DynamicPresenterFactory {
	
	public static function enable() {
		spl_autoload_register('DynamicPresenterFactory::autoload');
	}
	
	public static function autoload($class) {
		$loader = \Nette\Environment::getRobotLoader();
		if (strpos($class, "Presenter") && !array_key_exists($class, $loader->getIndexedClasses())) {
			eval("namespace AdminModule { class RentalPresenter extends AdminPresenter {} }");
		}
	}
}