<?php

namespace PersonalSiteModule;

use Nette;

abstract class BasePresenter extends \BasePresenter
{

	/**
	 * @persistent
	 * @var \Entity\Rental\Rental
	 */
	public $rental;

	public function beforeRender() {
		parent::beforeRender();

		$this->template->currentLanguage = $this->language;
	}


}
