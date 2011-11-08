<?php

namespace Forms;

use Nette\Forms\Form,
	Nette\Application as NA,
	Nette\Diagnostics\Debugger;

class Article extends \CoolForm {

    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		$this->addText('title', 'Title');
		$this->addTextArea('content', 'Content')
			->setRequired('Content must be filled');
		$this->addComboSelect('category', 'Category', $this->em->getRepository('Category')->fetchPairs('id', 'name'))
			->setRequired('Category must be filled');
		$this->addSelect('status', 'Category', array(
			\Article::STATUS_DRAFT => 'Draft',
			\Article::STATUS_PUBLISHED => 'Published'
		))->setRequired('Category must be filled');
		$this->addDateTimePicker('published', 'Published');
		$this->addSubmit('publish', 'Publish');
		$this->addSubmit('draft', 'Draft');
		$this->addSubmit('save', 'Save');
		$this->onSuccess[] = callback($this, 'onSave');
	}

	public function onSave(Form $form) {
		$this->parent->invalidateControl();

		//zistim ci neprisla poziadavnka na novu kategoriu
		if ($form->category->isNewValue()) {
			$category = new \Category(array(
				'name' => $form->category->getNewValue()
			));
			$this->em->persist($category);
		} else {
			$category = $this->em->find('Category', $form->category->getValue());
		}

		try {
			$id = (int)$this->getParam('id');
			if ($id > 0) {
				$article = $this->em->find('Article', $id);
				$article->setData($form->getValues());
				$article->setCategory($category);
				$this->em->persist($article);
				$this->em->flush();

				$this->flashMessage('The client has been updated.');
				if (!$this->parent->isAjax()) $this->parent->redirect('this');
			} else {
				$article = new \Article;
				$article->setData($form->getValues());
				$article->setUser($this->user);
				$article->setCategory($category);
				$this->em->persist($article);
				$this->em->flush();

				$this->flashMessage('The client has been added.');
				$this->parent->redirect('edit', $article->id);
			}
		} catch (PDOException $e) {
			Debugger::log($e->getMessage(), Debugger::ERROR);
			$this->flashMessage($e->getMessage(), 'error');
		}
    }
}
