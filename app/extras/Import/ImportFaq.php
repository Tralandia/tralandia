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
		$faqCategory->name = $this->createPhraseFromString('\Faq\Category', 'name', 'NATIVE', 'General questions', $en);
		$this->model->persist($faqCategory);

		$questionType = $this->createPhraseType('\Faq\Question', 'question', 'NATIVE');
		$answerType = $this->createPhraseType('\Faq\Question', 'answer', 'NATIVE');

		$questions = q('select * from faq');
		while ($question = mysql_fetch_array($questions)) {
			$faqQuestion = $context->faqQuestionRepositoryAccessor->get()->createNew(FALSE);
			$faqQuestion->category = $faqCategory;
			$faqQuestion->question = $this->createNewPhrase($questionType, $question['question_dic_id'], NULL, NULL, $en);
			$faqQuestion->answer = $this->createNewPhrase($answerType, $question['answer_dic_id'], NULL, NULL, $en);
			$this->model->persist($faqQuestion);
		}

		$this->model->flush();
	}

}