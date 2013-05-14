<?php

namespace AdminModule;

use Nette\Application\BadRequestException;
use Nette\Environment;
use Nette\Security\User;
use Security\SimpleAcl;

abstract class BasePresenter extends \SecuredPresenter {

	/**
	 * @autowire
	 * @var \AdminModule\Components\INavigationControlFactory
	 */
	protected $navigationFactory;

	public function beforeRender() {
		parent::beforeRender();
		$this->setRenderMode();
	}

	public function setRenderMode() {
		if(isset($this->params['display']) && $this->params['display'] == 'modal') {
			// $this->formMask->form->addClass .= ' ajax';
			$this->setLayout(FALSE);
			$this->template->display = 'modal';
		}
	}


	public function formatTemplateFiles() {
		$name = $this->getName();
		$presenter = substr($name, strrpos(':' . $name, ':'));
		$module = substr($this->getReflection()->getName(), 0, strpos($this->getReflection()->getName(), '\\'));
		$dir = APP_DIR . '/' . $module;

		return array(
			"$dir/templates/$presenter/$this->view.latte",
			"$dir/templates/$presenter.$this->view.latte",
			"$dir/templates/Admin/$this->view.latte"
		);
	}

	public function formatLayoutTemplateFiles() {
		$name = $this->getName();
		$presenter = substr($name, strrpos(':' . $name, ':'));
		$layout = $this->layout ? $this->layout : 'layout';
		$module = substr($this->getReflection()->getName(), 0, strpos($this->getReflection()->getName(), '\\'));
		$dir = APP_DIR . '/' . $module;
		$list = array(
			"$dir/templates/$presenter/@$layout.latte",
			"$dir/templates/$presenter.@$layout.latte",
			"$dir/templates/Admin/@$layout.latte"
		);
		do {
			$list[] = "$dir/templates/@$layout.latte";
			$dir = dirname($dir);
		} while ($dir && ($name = substr($name, 0, strrpos($name, ':'))));
		return $list;
	}

	public function createComponentNavigation(){
		$navigation = $this->navigationFactory->create($this->getUser());
		return $navigation;
	}

	public function actionFakeLogin($id)
	{
		$user = $this->userRepositoryAccessor->get()->find($id);
		if(!$user) {
			throw new BadRequestException;
		}

		$this->fakeLogin($user);
		$this->actionAfterLogin();
	}

	public function fakeLogin(\Entity\User\User $user)
	{
		$this->checkPermission($user, SimpleAcl::FAKE_IDENTITY);

		$fakeIdentity = $this->authenticator->fakeAuthenticate($user, $this->loggedUser);
		$this->login($fakeIdentity);
	}

	/**
	 * @acl(forPresenter=BasePresenter)
	 */
	public function actionDecodeNeon() {
		$input = $this->getHttpRequest()->getPost('input', '');
		try {
			$decoded = \Nette\Utils\Neon::decode($input);
			$output = \Nette\Diagnostics\Debugger::dump($decoded, TRUE);
		} catch(\Nette\Utils\NeonException $e) {
			$output = $e->getMessage();
		}

		$this->payload->output = $output;
		$this->sendPayload();
	}

	/**
	 * @acl(forPresenter=BasePresenter)
	 */
	public function actionLiveWysi() {

		$content = $this->getHttpRequest()->getPost('content', '');

		$type = \Service\Emailing\TemplateType::get(1);

		foreach ($this->availableVariables as $variable => $variableData) {
			$content = str_replace('[' . $variable . ']', $variableData['example'], $content);
		}

		$this->payload->content = $content;
		$this->sendPayload();

	}

	/**
	 * @acl(forPresenter=BasePresenter)
	 */
	public function actionSuggestion($serviceList, $property, $search, $language) {

		$language = \Service\Dictionary\Language::get($language);
		$suggestion = $serviceList::getSuggestions($property, $search, $language);

		// @todo dorobit replace premennych

		$this->payload->suggestion = $suggestion;
		$this->sendPayload();

	}

}
