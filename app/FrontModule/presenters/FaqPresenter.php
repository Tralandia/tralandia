<?php

namespace FrontModule;

class FaqPresenter extends BasePresenter
{



	public function renderDefault() {
		$this->template->categories = $this->getContext()->getService('doctrine.default.entityManager')->getDao(FAQ_CATEGORY_ENTITY)->findAll();
	}
}
