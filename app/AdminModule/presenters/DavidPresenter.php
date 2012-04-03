<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	DoctrineExtensions\NestedSet,
	Extras\Models\Service,
	Service\Dictionary as D,
	Service as S,
	Service\Log\Change as SLog;

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

		$s = S\Dictionary\Phrase::get(3);
		$t = S\Dictionary\Translation::get(4);
		$s->addTranslation($t);
		//$s->blabal('sdf');
		//$s = S\Location\LocationService::get(3);
		//$s->setSlug('smola');
		//$s->slug = 'asia';
		//$type = S\Location\Type::getBySlug('country');
		//$s = S\Location\LocationList::getBySlugInType('asia', array($type));
		//$s = S\Location\LocationService::getBySlugAndType('asia', $type);
		//$s = S\Company\Company::get(1);
		//$s->address = new \Extras\Types\Address(array('city' => 'Nesvady', 'country' => 'Slovakia'));
		//$s->save();
		//$s = S\Location\LocationList::getAll();
		debug($t->language->iso);
		debug($s->translations->toArray());
	}



	public function actionAddTranslation () {
		$p = D\Phrase::get(7);
		$t = D\Translation::get();
		$p->addTranslation($t);
		$t->language = D\Language::get(140);
		$t->translation = ' Toto je NEvebalizovaná veria prekladu		';
		$t->save();
		debug($p->type->entityName);
		debug($t->variations);
	}

	public function actionDuplicatePhrase($id) {
		$p = D\Phrase::get($id);
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
		$list = D\LanguageList::getBySupported(\Entity\Dictionary\Language::SUPPORTED);
		$list->returnAs('Services\Dictionary\LanguageService');
		debug(count($list));
		$i = 0;
		foreach ($list as $key => $val) {
			debug($val);
			$list->returnAs(D\LanguageList::RETURN_ENTITIES);
			if($i++ >= 5) break;

			//break;
		}
		$list->returnAs(D\LanguageList::RETURN_ENTITIES);
		//debug($list[$i]);
	
	}

	public function actionAddPhrase() {
		$dictionary = new D\Dictionary;

		$type = $dictionary->getType(1);
		$phrase = $dictionary->createPhrase(array(
			'type' => $type,
			'ready' => FALSE,
			'entityId' => FALSE,
		));
		//debug($phrase);
	}

	public function actionUpdatePhrase($id) {
		$phrase = new D\Phrase($id);
		//debug($phrase);

		$l1 = new D\Language(3);
		$l2 = new D\Language(1);
		$phrase->addLanguage($l1)->addLanguage($l2);

		$language = new D\Language(2);
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
		$translation = new D\Language($id);
		$translation->name = new D\Phrase(1);
		$translation->save();
		//debug($translation);
	}

	public function actionAddLanguage($id) {
		$language = new D\Language;
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
