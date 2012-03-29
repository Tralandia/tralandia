<?php

namespace AdminModule;

use \Services as S,
	\Services\Dictionary as D;

class CibiPresenter extends BasePresenter {

	public function actionDefault() {

		$d = D\LanguageList::get();
		debug($d);

	}

}
