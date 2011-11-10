<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings;

class UserPresenter extends BasePresenter {

	public function renderList() {
		
	}

	public function renderAdd() {
		$this->template->form = $this->getComponent('form');
	}

	public function renderEdit($id = 0) {
		$this->template->form = $this->getComponent('form');
	}

	public function actionDelete($id = 0) {

	}

	protected function createComponentForm($name) {
		return new \Tra\Forms\User($this, $name);
	}

	protected function createComponentGrid($name) {
		$grid = new \DataGrid\DataGrid;
		//$grid->setTranslator(Environment::getService('translator'));
		$grid->itemsPerPage = 20;
		$grid->multiOrder = FALSE;
		$grid->displayedItems = array(20, 50, 100);

		$dataSource = new \DataGrid\DataSources\Doctrine\QueryBuilder($this->em->getRepository('User')->getDataSource());
		$dataSource->setMapping(array(
			'id' => 'u.id',
			'country' => 'c.iso',
			'login' => 'u.login',
			'password' => 'u.password',
			'active' => 'u.active',
			'created' => 'u.created'
		));

		$grid->setDataSource($dataSource);
		
		$grid->addColumn('login', 'Login');
		$grid->addColumn('password', 'Password');
		$grid->addCheckboxColumn('active', 'Active');
		$grid->addColumn('country', 'Country');
		$grid->addDateColumn('created', 'Date', '%d.%m.%Y')->addDefaultSorting('desc');
		
		$grid->addActionColumn('Actions');
		$grid->addAction('Edit', 'edit', Html::el('span')->class('icon edit')->setText('Edit') , false);
		$grid->addAction('Delete', 'delete', Html::el('span')->class('icon delete')->setText('Delete'), false);

		return $grid;
	}
}
