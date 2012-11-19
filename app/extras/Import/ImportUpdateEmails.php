<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportUpdateEmails extends BaseImport {

	public function doImport($subsection = NULL) {

		$email = $this->context->emailTemplateRepository->findOneByOldId(6);

		$replace = array(
			'emailFrom' => 'sender_email',
			'siteDomain' => 'env_siteDomain',
			'message' => 'message',
			'objectLink' => 'rental_link',
		);

		$replaceTemp = array();
		foreach ($replace as $key => $value) {
			$replaceTemp["~$key~"] = "[$value]";
		}
		$replace = $replaceTemp;

		$body = $email->body;
		foreach ($body->translations as $translation) {
			$translation->translation = str_replace(array_keys($replace), array_values($replace), $translation->translation);
		}

		$layout = $this->context->emailLayoutRepositoryAccessor->get()->findOneByName('default');
		if(!$layout) {
			$layout = $this->context->emailLayoutEntityFactory->create();
			$this->model->persist($layout);
		}
		$layout->html = '<html><head></head><body>{include #content}</body></html>';


		$this->model->flush();
	}

}