<?php
namespace FrontModule;

class ContactPresenter extends BasePresenter {

	protected $faqCategoryRepositoryAccessor;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->faqCategoryRepositoryAccessor = $dic->faqCategoryRepositoryAccessor;
	}


	public function renderDefault() {

		$this->template->categories = $this->faqCategoryRepositoryAccessor->get()->findAll();

	}
}