<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Dictionary as D,
	Service as S,
	Service\Log as SLog,
	Nette\Application\Responses\TextResponse;

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

		$log = new S\Log\Change;
		$log->setType(new S\Log\Change\ChangeType(2));
		
		SLog\ChangeLog::bla($email, $service, $logType, $details);
		
		debug($log);
		debug($type);
	}

	public function actionHaha() {
		$p = new \Extras\Types\Phone('234234', \Service\Location\Location::get(3)->getMainEntity());
		debug($p->encode());
	}

	public function actionTest() {

		//$s = S\Medium\Medium::createFromUrl('http://www.tralandia.sk/u/01/13220628889049.png');
		//debug($s);
		$data = array(
			array('name'=>'en'),
			array('name'=>'fi'),
			array('name'=>'fr'),
			array('name'=>'hh'),
			array('name'=>'hr'),
			array('name'=>'hu'),
			array('name'=>'rn'),
			array('name'=>'sd'),
			array('name'=>'SK'),
			array('name'=>'ze'),
		);
		$this->template->data = $data;

		// $t = new \Extras\Cache\RouterCaching($this->context->routerCache);
		// $t->generateSegments();
		// $t->generateDomain();


		$f = $this->context->ticketFetcher;
		debug($f->getMessages('unseen', '', true));		
	}

	public function createComponentTabControl($name) {

		$tabControl = new \BaseModule\Components\TabControl\TabControl($this, $name);


		$t = $tabControl->addTab('tab1');
		$t->header = 1;
		$t->active = true;
		$t->content = 'content 1';

		$t = $tabControl->addTab('tab2');
		$t->header = 2;
		$t->content = $tabControl2 = new \BaseModule\Components\TabControl\TabControl($t, 'test');

		
		$t = $tabControl2->addTab('tab21');
		$t->header = 1;
		$t->active = true;
		$t->content = 'subtab 1';

		$t = $tabControl->addTab('tab3');
		$t->header = 3;
		$t->content = new \FrontModule\Components\RegistrationPage\Registration($t, 'registrationPage');

		return $tabControl;

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

		d($this->context->locationServiceFactory->create());

		// $robot = $this->context->generatePathSegmentsRobot;
		// $robot->run();


		// // pripravim si template a layout
		// $template = $this->context->emailTemplateRepositoryAccessor->get()->find(1);
		// $layout = $this->context->emailLayoutRepositoryAccessor->get()->find(1);

		// // pripravim si odosielatela
		// $sender = $this->context->userRepositoryAccessor->get()->findOneByLogin('infoubytovanie@gmail.com');

		// // pripravim si prijimatela
		// $receiver = $this->context->userRepositoryAccessor->get()->findOneByLogin('pavol@paradeiser.sk');

		// // pripravim si rental
		// $rental = $this->context->rentalRepositoryAccessor->get()->find(1);

		// // ponastavujem compiler
		// $emailCompiler = $this->context->emailCompiler;
		// $emailCompiler->setTemplate($template);
		// $emailCompiler->setLayout($layout);
		// $emailCompiler->setPrimaryVariable('receiver', 'visitor', $receiver);
		// $emailCompiler->addVariable('sender', 'visitor', $sender);
		// $emailCompiler->addVariable('rental', 'rental', $rental);
		// $emailCompiler->addCustomVariable('message', 'Toto je sprava pre teba!');
		// $html = $emailCompiler->compile();

		// $this->sendResponse(new TextResponse($html));
	}
	
	public function renderAdd() {
		$this->template->form = $this->getComponent('form');
	}

	protected function createComponentForm($name) {
		return new \Tra\Forms\User($this, $name);
	}

}
