<?php

namespace AdminModule;


class DictionaryPhrasePresenter extends BasePresenter {

	public function renderEdit() {
	}

	/**
	 * @return Forms\Phrase\Edit
	 */
	protected function createComponentEditForm($name)
	{
		$comp = new Forms\Phrase\Edit($this, $name);
	
		return $comp;
	}


}
