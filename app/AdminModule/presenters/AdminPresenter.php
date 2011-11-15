<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	\Nette\Application\UI\Form;

class AdminPresenter extends BasePresenter {

	public function renderList() {
		
		$this->template->list = \Nette\ArrayHash::from(array(
			'name' => ucfirst($this->getParams()->form) . ' ' . ucfirst($this->action)
		));
	}
/*
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
		$grid = new \EditableDataGrid\DataGrid;
		$grid->setTranslator(Environment::getService('translator'));
		$grid->itemsPerPage = 20;
		$grid->multiOrder = FALSE;
		$grid->displayedItems = array(20, 50, 100);

		$dataSource = new \DataGrid\DataSources\Doctrine\QueryBuilder($this->em->getRepository('Rental')->getDataSource());
		$dataSource->setMapping(array(
			'id' => 'r.id',
			'country' => 'c.iso',
			'user' => 'u.login',
			'nameUrl' => 'r.nameUrl',
			'status' => 'r.status',
			'created' => 'r.created'
		));

		$grid->setDataSource($dataSource);
		$grid->addColumn('country', 'ISO');
		$grid->addColumn('user', 'User');
		$grid->addColumn('nameUrl', 4242);
		$grid->addColumn('status', 'Status');
		$grid->addDateColumn('created', 'Date', '%d.%m.%Y')->addDefaultSorting('desc');
		$grid->addActionColumn('Actions');
		$grid->addAction('Edit', 'edit', Html::el('span')->class('icon edit')->setText('Edit') , false);
		$grid->addAction('Delete', 'delete', Html::el('span')->class('icon delete')->setText('Delete'), false);

		
		//$grid->setEditForm($this->getComponent('gridForm'));
		//debuge($this->getComponent('gridForm'));
		$grid->setEditForm($this->getComponent('gridForm'));
		//$grid->addEditableField('country');
		$grid->setContainer('Rental');
		$grid->addEditableField('user');
		$grid->addEditableField('nameUrl');
		//$grid->addEditableField('status');
		$grid->onDataReceived[] = array($this, 'onDataRecieved');
		$grid->onInvalidDataReceived[] = array($this, 'onDataRecieved');
		$grid->onInvalidDataReceived[] = array($this, 'onInvalidDataRecieved');

		return $grid;
	}
	
	function createComponentGridForm($name) {
		return new \Tra\Forms\Rental($this, $name);
	}

	function onDataRecieved($cisloRadku, \Nette\Forms\IControl $policko, $origSha1) {
		$this->flashMessage("Data přijata na řádku ".$cisloRadku.", data: ".$policko->value." a původní sha1 zadaných dat (kvůli současným úpravám více uživatelů, složí k porovnání s aktuální hodnotou v DB):".$origSha1,"info");
	}

	function onInvalidDataRecieved($cisloRadku, \Nette\Forms\IControl $policko, $origSha1) {
		$this->flashMessage("Přijatá data jsou neplatná, protože neprošla validací!","error");
	}
	*/
}
