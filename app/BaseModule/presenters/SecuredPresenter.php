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
		 	if (!$this->user->isAllowed($module.'Module', $this->action)) {
		 		$this->accessDeny();
		 		//$this->restoreRequest($this->getPreviousBackLink());
		 	}
		 }

	}

}
