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

		//$s = S\Location\LocationService::getBySlug('asia');
		$s = S\Location\LocationService::get(3);
		//$s->setSlug('smola');
		$s->slug = 'asia';
		$type = S\Location\TypeService::getBySlug('country');
		// $s = S\Location\LocationList::getBySlugInType('asia', array($type));
		$s = S\Location\LocationService::getBySlugAndType('asia', $type);
		debug($s);

	}

	public function actionAddTranslation () {
		$p = D\PhraseService::get(7);
		$t = D\TranslationService::get();
		$p->addTranslation($t);
		$t->language = D\LanguageService::get(140);
		$t->translation = ' Toto je NEvebalizovaná veria prekladu		';
		$t->save();
		debug($p->type->entityName);
		debug($t->variations);
	}

	public function actionDuplicatePhrase($id) {
		$p = D\PhraseService::get($id);
		$pNew = $p->duplicate(TRUE);
		debug($p->translations->toArray());
		debug($pNew);
	}

	public function actionAddTask() {
		$typeName = 'test';
		$attributes = array();
		$params = array();
		$task = \Services\Autopilot\Autopilot::addTask($typeName, $attributes, $params);
		debug($task->getMainEntity());

		$typeName = 'improveRental';
		$attributes = array();
		$params = array();		
		$task = \Services\Autopilot\Autopilot::addTask($typeName, $attributes, $params);
		debug($task->getMainEntity());
	}

	public function actionListTest() {
		$list = D\LanguageList::getBySupported(\Entities\Dictionary\Language::SUPPORTED);
		$list->returnAs('Services\Dictionary\LanguageService');
		debug(count($list));
		$i = 0;
		foreach ($list as $key => $val) {
			debug($val);
			$list->returnAs(\Extras\Models\ServiceList::RETURN_ENTITIES);
			if($i++ >= 5) break;

			//break;
		}
		$list->returnAs(\Extras\Models\ServiceList::RETURN_ENTITIES);
		//debug($list[$i]);
	
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
