<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Strings;

class DavidPresenter extends BasePresenter {


	public function actionList() {

		$host = 'www.sk.tra.com';

		$mask = '<language>.<country>.<domain>.com';

		$patter = '~^(?P<langauge>\w+)\.(?P<country>\w+)\.(?P<domain>\w+)\.com$~';
		$patter = '~^(?P<langauge>\w+)\.(?P<country>\w+)\.(?P<domain>\w+)\.com$~';

		$patter = Strings::replace($mask, '~<\w+>~', function ($m) {
			return '(?P'.$m[0].'\w+)';
		});
		$patter = str_replace('.', '\.', $patter);
		d($patter);

	}


	

}
