<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportFaq extends BaseImport {

	public function doImport($subsection = NULL) {
		$context = $this->context;
		$model = $this->model;

		$en = $context->languageRepositoryAccessor->get()->findOneByIso('en');

		// Category 1
		$faqCategory = $context->faqCategoryRepositoryAccessor->get()->createNew(FALSE);
		$faqCategory->name = $this->createPhraseFromString('\Faq\Category', 'name', 'NATIVE', 'Category One Lorem Ipsum', $en);
		$this->model->persist($faqCategory);

		// Question 1/1
		$faqQuestion = $context->faqQuestionRepositoryAccessor->get()->createNew(FALSE);
		$faqQuestion->category = $faqCategory;
		$faqQuestion->question = $this->createPhraseFromString('\Faq\Question', 'question', 'NATIVE', 'This is my first question to first category', $en);
		$faqQuestion->answer = $this->createPhraseFromString('\Faq\Question', 'answer', 'NATIVE', 'This is the answer to the first question in first category', $en);
		$this->model->persist($faqQuestion);

		// Question 1/2
		$faqQuestion = $context->faqQuestionRepositoryAccessor->get()->createNew(FALSE);
		$faqQuestion->category = $faqCategory;
		$faqQuestion->question = $this->createPhraseFromString('\Faq\Question', 'question', 'NATIVE', 'This is my second question to first category', $en);
		$faqQuestion->answer = $this->createPhraseFromString('\Faq\Question', 'answer', 'NATIVE', 'This is the answer to the second question in first category', $en);
		$this->model->persist($faqQuestion);


		// Category 2
		$faqCategory = $context->faqCategoryRepositoryAccessor->get()->createNew(FALSE);
		$faqCategory->name = $this->createPhraseFromString('\Faq\Category', 'name', 'NATIVE', 'Category 2222 Lorem Ipsum', $en);
		$this->model->persist($faqCategory);

		// Question 2/1
		$faqQuestion = $context->faqQuestionRepositoryAccessor->get()->createNew(FALSE);
		$faqQuestion->category = $faqCategory;
		$faqQuestion->question = $this->createPhraseFromString('\Faq\Question', 'question', 'NATIVE', 'This is my first question to second category', $en);
		$faqQuestion->answer = $this->createPhraseFromString('\Faq\Question', 'answer', 'NATIVE', 'This is the answer to the first question in second category', $en);
		$this->model->persist($faqQuestion);

		// Question 2/2
		$faqQuestion = $context->faqQuestionRepositoryAccessor->get()->createNew(FALSE);
		$faqQuestion->category = $faqCategory;
		$faqQuestion->question = $this->createPhraseFromString('\Faq\Question', 'question', 'NATIVE', 'This is my second question to second category', $en);
		$faqQuestion->answer = $this->createPhraseFromString('\Faq\Question', 'answer', 'NATIVE', 'This is the answer to the second question in second category', $en);
		$this->model->persist($faqQuestion);

		$this->model->flush();
	}

}