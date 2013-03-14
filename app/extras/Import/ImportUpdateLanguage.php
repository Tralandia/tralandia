<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportUpdateLanguage extends BaseLanguagesImport {

	public function doImport($subsection = NULL) {

		$languageRepository = $this->context->languageRepositoryAccessor->get();

		foreach ($this->languageOptions as $iso => $data) {
			$e = $languageRepository->findOneByIso($iso);
			foreach ($data as $key => $value) {
				$e->{$key} = $value;
			}
		}

		$this->model->flush();

	}

}