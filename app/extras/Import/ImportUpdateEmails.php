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

	const DELETE = NULL;

	public function doImport($subsection = NULL) {

		$replace = array(
			'default' => [
				'emailFrom' => 'sender_email',
				'siteDomain' => 'env_siteDomain',
				'siteName' => 'env_siteName',
				'message' => 'message',
				'objectLink' => 'rental_link',
				'loginLink' => 'env_loginLink',
				'email' => 'owner_email',
				'password' => 'owner_password',
				'RID' => self::DELETE,
			],
		);

		$emails = $this->context->emailTemplateRepositoryAccessor->get()->findAll();

		foreach($emails as $email) {

			$replaceMerge = array_merge(
				$replace['default'],
				\Nette\Utils\Arrays::get($replace, $email->getSlug(), array())
			);

			$replaceTemp = array();
			foreach ($replaceMerge as $key => $value) {
				if($value === self::DELETE) {
					$replaceTemp["~$key~"] = '';
				} else {
					$replaceTemp["~$key~"] = "[$value]";
				}
			}

			$body = $email->body;
			foreach ($body->translations as $translation) {
				$translationText = str_replace(
					array_keys($replaceTemp),
					array_values($replaceTemp),
					$translation->getTranslation()
				);

				$translation->setTranslation($translationText);
			}

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