<?php

namespace FrontModule;

class FaqPresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \FrontModule\Forms\IContactFormFactory
	 */
	protected $contactFormFactory;

	protected $faqCategoryRepositoryAccessor;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->faqCategoryRepositoryAccessor = $dic->faqCategoryRepositoryAccessor;
	}


	public function renderDefault() {
		$this->template->categories = $this->faqCategoryRepositoryAccessor->get()->findAll();
	}

//	protected function createComponentContactForm()
//	{
//		$form = $this->contactFormFactory->create(NULL, $this->languageRepositoryAccessor->get()->find(144));
//
//		$form->onSuccess[] = function ($form) {
//			if ($form->valid) {
//				$this->presenter->flashMessage('#Kontakt form odoslany', 'success');
//				$form->presenter->redirect('this');
//			}
//		};
//
//		if ($this->isAjax()) {
//			$this->invalidateControl('contactForm');
//	    }
//
//		return $form;
//	}

}
