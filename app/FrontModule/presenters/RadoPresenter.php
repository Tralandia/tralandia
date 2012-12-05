<?php

namespace FrontModule;

class RadoPresenter extends BasePresenter {

	public function actionGPS() {
		d($this->normalizeGPS());
	}

	public function normalizeGPS($latitude, $longitude) {

	}
}
