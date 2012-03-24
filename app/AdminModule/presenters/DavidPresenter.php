<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	DoctrineExtensions\NestedSet,
	Extras\Models\Service,
	Services\Dictionary as D,
	Services as S,
	Services\Log\Change as SLog;

class DavidPresenter extends BasePresenter {

	public function beforeRender() {
		parent::beforeRender();
		$this->setView('list');
	}

	public function actionLog() {
		$data = array(
			'old' => array(),
			'new' => array(),
		);

		$log = new S\Log\Change\ChangeLog;
		$log->setType(new S\Log\Change\ChangeType(2));
		
		SLog\ChangeLog::bla($email, $service, $logType, $details);
		
		debug($log);
		debug($type);
	}

	public function actionTest() {

		$service = S\Contact\TypeService::getByClass('Email');
		debug($service);
		
	}

	public function actionAddPhrase() {
		$dictionary = new D\DictionaryService;

		$type = $dictionary->getType(1);
		$phrase = $dictionary->createPhrase(array(
			'type' => $type,
			'ready' => FALSE,
			'entityId' => FALSE,
		));
		//debug($phrase);
	}

	public function actionUpdatePhrase($id) {
		$phrase = new D\PhraseService($id);
		//debug($phrase);

		$l1 = new D\LanguageService(3);
		$l2 = new D\LanguageService(1);
		$phrase->addLanguage($l1)->addLanguage($l2);

		$language = new D\LanguageService(2);
		$phrase->removeLanguage($language);

		$phrase->createTranslation(array(
			'language' => $l1,
			'translation' => 'Slovensky',
		));

		$phrase->createTranslation(array(
			'language' => $l2,
			'translation' => 'Slovak',
		));

		$phrase->save();
		//debug($phrase);
	}

	public function actionUpdateLanguage($id) {
		$translation = new D\LanguageService($id);
		$translation->name = new D\PhraseService(1);
		$translation->save();
		//debug($translation);
	}

	public function actionAddLanguage($id) {
		$language = new D\LanguageService;
		$language->iso = $id;
		$language->supported = false;
		$language->save();
		//debug($language);
	}

	public function actionList() {


		//\Services\CurrencyService::preventFlush();
/*
		for ($i = 0; $i < 2; $i++) {
			$service = \Services\CurrencyService::get();
			$service->iso = \Nette\Utils\Strings::random(5, 'A-Z');
			$service->exchangeRate = 1;
			$service->decimalPlaces = 1;
			$service->rounding = 'r';
			$service->created = new \Nette\DateTime;
			$service->updated = new \Nette\DateTime;
			$service->save();
		}
*/
		//\Services\CurrencyService::flush();


/*		$n = new \Services\CurrencyService;
		foreach ($n->getDataSource()->getQuery()->getResult() as $row) {
			$c = \Services\CurrencyService::get($row);
		}
		debug($c);
*/

		$a = \Services\CurrencyService::get(12);
		$b = \Services\CurrencyService::get(12);

		if($a === $b) debug('$a === $b', $a);
		else debug('$a !== $b', $a, $b);


/*		$list = new \Services\CurrencyList;
		foreach ($list as $entity) {
			$c = \Services\CurrencyService::get($entity);
			//debug($entity, $c);
		}
*/	}
	
	public function renderAdd() {
		$this->template->form = $this->getComponent('form');
	}

	protected function createComponentForm($name) {
		return new \Tra\Forms\User($this, $name);
	}

}
