<?php
namespace AdminModule\Components;

use BaseModule\Components\BaseControl;

class GaleryManager extends BaseControl {

	public $page;

	/**
	 * Renders manager.
	 * @return void
	 */
	public function render() {
		//$this->invalidateControl();
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/template.latte');
		$template->render();
	}

	public function handleSaveImageSort() {
		try {
			$sort = $this->presenter->parameter['sort'];
			$this->getModel('imageSelection')->updateSort($sort);
			$this->getPresenter()->getPayload()->success = true;
		} catch (Exception $e) {
			$this->getPresenter()->getPayload()->error = $e->getMessage();
		}
		//$this->getPresenter()->sendPayload();
	}

	public function handleImageDelete($image_id) {
		$this->invalidateControl();
		try {
			$image = $this->getModel('image')->get($image_id);
			$this->getModel('image')->forceDelete($image);
			$this->getPresenter()->getPayload()->success = true;
		} catch (Exception $e) {
			$this->getPresenter()->getPayload()->error = $e->getMessage();
		}
	}

	public function handleImageUpload($file) {
		$this->invalidateControl();
		try {
			$this->getModel('image')->save($file, $this->page);
			$this->getPresenter()->getPayload()->success = true;
		} catch (Exception $e) {
			$this->getPresenter()->getPayload()->error = $e->getMessage();
		}
	}
}