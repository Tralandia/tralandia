<?php


abstract class SecuredPresenter extends BasePresenter {

	protected function startup() {
		parent::startup();

		if (!$this->user->isLoggedIn()) {
			//$backlink = $this->storeRequest();
//		 	$this->redirect(':Front:Sign:in', array('backlink' => $backlink));
			$this->redirect(':Front:Sign:in');
		} else {
			list($module, ) = explode(':', $this->name, 2);
			$this->checkPermission($module.'Module');
			$this->checkPermission($this->name, $this->action);
		}

	}

}
