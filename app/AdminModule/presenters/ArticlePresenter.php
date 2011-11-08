<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings;

class ArticlePresenter extends BasePresenter {
	
	public function renderDefault() {	
		$this->template->articles = $this->em->getRepository('Article')->findAll();
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
		return new \Forms\Article($this, $name);
	}
	
	protected function createComponentSpeedUpload($name) {
		$control = new \SpeedUpload($this, $name);
		$control->multi = false;
		$control->onSuccess[] = callback($this, 'onUpload');
		$control->onError[] = callback($this, 'onErrorUpload');
		return $control;
	}
	
	protected function createComponentGrid($name) {
		$grid = new \DataGrid\DataGrid;
		$grid->setTranslator(Environment::getService('translator'));
		$grid->itemsPerPage = 20;
		$grid->multiOrder = false;
		$grid->displayedItems = array(20, 50, 100);

		$dataSource = new \DataGrid\DataSources\Doctrine\QueryBuilder($this->em->getRepository('Article')->getDataSource());
		$dataSource->setMapping(array(
			'id' => 'a.id',
			'title' => 'a.title',
			'content' => 'a.content',
			'category' => 'c.name',
			'published' => 'a.published'
		));

		$grid->setDataSource($dataSource);
		$grid->addColumn('title', 'Title')->formatCallback[] = function($value, $item) {
			return $value ? Strings::truncate($value, 100) : Html::el('i')->add(Strings::truncate(strip_tags($item['content']), 100));
		};
		$grid->addColumn('category', 'Category');
		$grid->addDateColumn('published', 'Published', '%d.%m.%Y')->addDefaultSorting('desc');
		$grid->addActionColumn('Actions');
		$grid->addAction('Edit', 'edit', Html::el('span')->class('icon edit'), false);
		$grid->addAction('Delete', 'delete', Html::el('span')->class('icon delete'), false);

		return $grid;
	}

	public function onUpload(\Nette\Http\FileUpload $file) {
		$this->invalidateControl();
		$config = Environment::getConfig('image');
		$id = $this->getParams()->id;
		
		if ($file->isOk() && $file->isImage()) {
			$filename = '/' . $id . '.' . \Tools::getExt($file->getName());
			$thumbname = '/' . $id . '-' . $config->width . 'x' . $config->height . '.' . \Tools::getExt($file->getName());

			// ulozenie zaznamu o obrazku
			$article = $this->em->find('Article', $id);
			$article->image = $config->baseUrl . $filename;
			$this->template->article = $article;
			$this->em->persist($article);
			$this->em->flush();
			
			// skopcenie obrazku
			$file->move($config->basePath . $filename);
			
			// vytvorenie miniatury
			$image = $file->toImage();
			$image->resizeCrop($config->width, $config->height);
			$image->alphaBlending(false);
			$image->saveAlpha(true);
			$image->save($config->basePath . $thumbname);
			
			// a printim hlasku
			$this->flashMessage('Image was uploaded.');
		}
	}
	
	public function onErrorUpload($message) {
		Debugger::log($message);
	}
}