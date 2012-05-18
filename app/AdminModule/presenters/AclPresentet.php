<?php

namespace AdminModule;

use Nette\ArrayHash;
use Nette\Utils\Finder;

class AclPresenter extends BasePresenter {

	protected $presenterList;
	protected $entityList;
	
	protected $presenterActions;

	public function renderList() {
		$list = $this->getPresenterList();
		$entitesList = $this->getEntityList();
		$this->template->list = $list;
		$this->template->entitesList = $entitesList;
	}

	protected function getPresenterList() {
		return $this->context->properties['acl']['presenters'];
	}

	protected function getPresenterAclConfig($name) {
		$filename = $this->context->parameters['acl']['presentersDir'] . '/' . $name . '.neon';
		if(!is_file($filename)) return array();
		$config = new \Nette\Config\Loader;
		return $this->presenterList = $config->load($filename);
	}

	protected function getEntityList() {
		if(!$this->entityList) {
			$this->entityList = array();
			foreach (Finder::findFiles('*.php')->from(APP_DIR . '/models/Entity/') as $key => $file) {
				list($x, $nameTemp) = explode('/models/', $key, 2);
				$nameTemp = str_replace(array('/', '.php'), array('\\', ''), $nameTemp);
				list(, $nameTemp) = explode('\\', $nameTemp, 2);
				$this->entityList[$nameTemp] = $nameTemp; 
			}
		}
		return $this->entityList;
	}

	public function actionPresenterEdit($presenterName) {
		$list = $this->getPresenterList();
		$this->presenterActions = $list[$this->params['presenterName']];

		$form = $this->getComponent('presenterForm');
		$form->setDefaults($this->getPresenterAclConfig($this->params['presenterName']));

		$this->template->resource = $presenterName;
		$this->template->presenterActions = $this->presenterActions;
		$this->template->roles = $roles = \Service\User\RoleList::getPairs('id', 'name');;
	}

	/**
	 * @return Forms\Acl\PresenterEdit
	 */
	protected function createComponentPresenterForm($name) {
		$comp = new Forms\Acl\PresenterEdit($this, $name, $this->presenterActions);
		$comp->destinationDir = $this->context->parameters['acl']['presentersDir'];
		$comp->presenterName = $this->params['presenterName'];
	
		return $comp;
	}
}
