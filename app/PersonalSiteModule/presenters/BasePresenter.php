<?php

namespace PersonalSiteModule;

use Nette;

abstract class BasePresenter extends \BasePresenter
{


	public function beforeRender() {
		parent::beforeRender();

		$this->template->currentLanguage = $this->language;

	}


}
