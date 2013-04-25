<?php

namespace FrontModule;

class AboutUsPresenter extends BasePresenter
{

	protected $userSiteReviewRepositoryAccessor;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->userSiteReviewRepositoryAccessor = $dic->userSiteReviewRepositoryAccessor;
	}

	public function renderDefault() {

		$this->template->testimonials = $this->userSiteReviewRepositoryAccessor->get()->findAll();

	}

}
