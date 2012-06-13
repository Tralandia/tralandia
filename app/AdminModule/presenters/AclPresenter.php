<?php

namespace AdminModule;

use Nette\ArrayHash;
use Nette\Utils\Finder;
use Nette\Utils\Strings;
use Nette\Reflection\ClassType;

class AclPresenter extends BasePresenter {

	protected $presenterList;
	protected $entityList;
	
	protected $presenterActions;
	protected $entityActions;

	protected $roles;

	protected function startup() {
		parent::startup();
		$this->roles = \Service\User\RoleList::forAcl();
	}

	protected function getPresenterList() {
		// $list = $this->context->parameters['acl']['presenters'];
		// $newList = array();
		// foreach ($list as $key => $value) {
		// 	$newList[str_replace(':', '-', $key)] = $value;
		// }
		// return $newList;
		if(!$this->presenterList) {
			$presenterList = array();
			
			# AdminModule
			foreach (Finder::findFiles('*Presenter.php')->from(APP_DIR . '/AdminModule/presenters') as $key => $file) {
				$resource = 'Admin:' . $file->getBasename('Presenter.php');
				$presenterList[$resource] = 'AdminModule-' . $file->getBasename('.php'); 
			}

			# dinamic presenters
			foreach (Finder::findFiles('*Presenter.php')->from(TEMP_DIR . '/presenters') as $key => $file) {
				$resource = 'Admin:' . $file->getBasename('Presenter.php');
				$presenterList[$resource] = 'AdminModule-' . $file->getBasename('.php');;
			}

			# FrontModule
			foreach (Finder::findFiles('*Presenter.php')->from(APP_DIR . '/FrontModule/presenters') as $key => $file) {
				$resource = 'Front:' . $file->getBasename('Presenter.php');
				$presenterList[$resource] = 'FrontModule-' . $file->getBasename('.php');; 
			}
			asort($presenterList);
			$this->presenterList = $presenterList;
		}
		return $this->presenterList;
	}

	protected function getEntityList() {
		if(!$this->entityList) {
			$this->entityList = array();
			foreach (Finder::findFiles('*.php')->from(APP_DIR . '/models/Entity/') as $key => $file) {
				list($x, $nameTemp) = explode('/models/', $key, 2);
				$nameTemp = str_replace(array('/', '.php'), array('_', ''), $nameTemp);
				// list(, $nameTemp) = explode('\\', $nameTemp, 2);
				$this->entityList[$nameTemp] = $nameTemp; 
			}
		}
		return $this->entityList;
	}

	protected function getPresenterActions($presenterName) {
		$actions = array();
		$reflection = new ClassType('\\' . str_replace('-', '\\', $presenterName));
		foreach ($reflection->getMethods() as $key => $value) {
			if(Strings::startsWith($value->getName(), 'action') || Strings::startsWith($value->getName(), 'render')) {
				$aclAnn = $value->getAnnotation('acl');
				if($aclAnn && $aclAnn->forPresenter != $reflection->getShortName()) continue;
				$actions[substr($value->getName(), 6)] = '';
			}
		}
		return $actions;
	}

	protected function getPresenterAclConfig($name) {
		$filename = $this->context->parameters['acl']['presentersDir'] . '/' . $name . '.neon';
		if(!is_file($filename)) return array();
		$config = new \Nette\Config\Loader;
		return $config->load($filename);
	}

	protected function getEntityAclConfig($name) {
		debug($name);
		$filename = $this->context->parameters['acl']['entitiesDir'] . '/' . $name . '.neon';
		if(!is_file($filename)) return array();
		$config = new \Nette\Config\Loader;
		return $config->load($filename);
	}

	protected function getEntityProperties($entityName) {
		$reflection = \Nette\Reflection\ClassType::from($entityName);
		return $reflection->getProperties();
	}

	public function renderList() {
		$list = $this->getPresenterList();
		$entitesList = $this->getEntityList();
		$this->template->list = $list;
		$this->template->entitesList = $entitesList;
	}

	public function actionPresenterEdit($presenterName) {
		$this->presenterActions = $this->getPresenterActions($presenterName);

		$form = $this->getComponent('presenterForm');
		$form->setDefaults($this->getPresenterAclConfig($presenterName));

		$this->template->resource = $presenterName;
		$this->template->presenterActions = $this->presenterActions;
		$this->template->roles = $this->roles;
	}

	/**
	 * @return Forms\Acl\PresenterEdit
	 */
	protected function createComponentPresenterForm($name) {
		$comp = new Forms\Acl\PresenterEdit($this, $name, $this->presenterActions, $this->roles);
		$comp->destinationDir = $this->context->parameters['acl']['presentersDir'];
		$comp->presenterName = $this->params['presenterName'];
	
		return $comp;
	}

	public function actionEntityEdit($entityName) {
		$entityDefaultVault = $this->getEntityAclConfig($entityName);
		$entityName = str_replace('_', '\\', $entityName);
		$list = $this->getEntityList();
		$this->entityActions = $this->getEntityProperties($entityName);

		$form = $this->getComponent('entityForm');
		$form->setDefaults($entityDefaultVault);

		$this->template->resource = $entityName;
		$this->template->entityActions = $this->entityActions;
		$this->template->roles = $this->roles;
	}

	/**
	 * @return Forms\Acl\EntityEdit
	 */
	protected function createComponentEntityForm($name) {
		$comp = new Forms\Acl\EntityEdit($this, $name, $this->entityActions, $this->roles);
		$comp->destinationDir = $this->context->parameters['acl']['entitiesDir'];
		$comp->entityName = $this->params['entityName'];
	
		return $comp;
	}


}
