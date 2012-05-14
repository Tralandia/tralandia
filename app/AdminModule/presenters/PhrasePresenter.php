<?php

namespace AdminModule;


class PhrasePresenter extends BasePresenter {

	public function actionEdit() {

	}

	/**
	 * @return Forms\Phrase\Edit
	 */
	protected function createComponentEdtiForm($name)
	{
		$comp = new Forms\Phrase\Edit($this, $name);
	
		return $comp;
	}


}
