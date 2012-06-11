<?php

namespace AdminModule;

class CibiPresenter extends BasePresenter {

	public function actionDefault() {

		$recurenceTask = \Service\Autopilot\Autopilot::createRecurrence(
			\Service\Autopilot\Task::get(2),
			"+60 min"
		);

	}

}