<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings;

class RentalPresenter extends BasePresenter {

	public function renderList() {
		
	}

	public function renderAdd() {
		$this->template->form = $this->getComponent('form');
	}

	public function renderEdit($id = 0) {
		$form = $this->getComponent('form');
		$row = $this->em->find('Article', $id);

		if (!$row) {
			throw new NA\BadRequestException('Record not found');
		}
		if (!$form->isSubmitted()) {
			$form->setDefaults($row->toFormArray());
		}

		$this->template->article = $row;
		$this->template->form = $this->getComponent('form');
	}

	public function actionDelete($id = 0) {
		$row = $this->em->find('Article', $id);
		if (!$row) {
			throw new NA\BadRequestException('Record not found');
		}

		$this->em->remove($row);
		$this->em->flush();
		$this->flashMessage('Article has been deleted.');
		$this->redirect('default');
	}

	protected function createComponentForm($name) {
		return new \Tra\Forms\Rental($this, $name);
	}

	protected function createComponentGrid($name) {
		$grid = new \DataGrid\DataGrid;
		//$grid->setTranslator(Environment::getService('translator'));
		$grid->itemsPerPage = 20;
		$grid->multiOrder = false;
		$grid->displayedItems = array(20, 50, 100);

		$dataSource = new \DataGrid\DataSources\Doctrine\QueryBuilder($this->em->getRepository('Rental')->getDataSource());
		$dataSource->setMapping(array(
			'id' => 'r.id',
			'country' => 'c.iso',
			'user' => 'u.name',
			'url' => 'u.nameUrl',
			'status' => 'r.status',
			'created' => 'r.created'
		));

		$grid->setDataSource($dataSource);
		$grid->addColumn('country', 'ISO');
		$grid->addColumn('user', 'User');
		$grid->addColumn('url', 4242);
		$grid->addColumn('status', 'Status');
		$grid->addDateColumn('created', 'Date', '%d.%m.%Y')->addDefaultSorting('desc');
		$grid->addActionColumn('Actions');
		$grid->addAction('Edit', 'edit', Html::el('span')->class('icon edit'), false);
		$grid->addAction('Delete', 'delete', Html::el('span')->class('icon delete'), false);

		return $grid;
	}
}
