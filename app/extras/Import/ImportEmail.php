<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Dictionary as D,
	Service as S,
	Service\Log as SLog;

class ImportEmail extends BaseImport {

	public function doImport($subsection = NULL) {
		$context = $this->context;
		$model = $this->model;

		$this->languagesByOldId = getNewIdsByOld('\Language');

		$subjectType = $this->createPhraseType('\Email\Template', 'subject', 'ACTIVE');
		$bodyType = $this->createPhraseType('\Email\Template', 'body', 'ACTIVE');

		$r = q('select * from emails');
		while($x = mysql_fetch_array($r)) {
			$template = $context->emailTemplateEntityFactory->create();
			$template->slug = $x['name'];
			$template->name = $x['name'];
			$template->subject = $this->createNewPhrase($subjectType, $x['subject_dic_id']);
			$template->body = $this->createNewPhrase($bodyType, $x['body_html_dic_id'], NULL, array('html' => TRUE));
			//$template->language = $context->languageRepositoryAccessor->get()->find($this->languagesByOldId[$x['source_language_id']]);
			$template->oldId = $x['id'];
			//debug($template); return;
			$model->persist($template);
		}
		$model->flush();
	}
}