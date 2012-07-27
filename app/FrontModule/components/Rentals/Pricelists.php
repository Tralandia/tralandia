<?php 
namespace FrontModule\Components\Rentals;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class Pricelists extends \BaseModule\Components\BaseControl {

	private $pricelists;
	private $currentLanguage;

	public function __construct($parent, $name, $pricelists, $currentLanguage) {
		$this->pricelists = $pricelists;
		$this->currentLanguage = $currentLanguage;
		parent::__construct($parent, $name);
	}

	public function render() {

		$template = $this->template;
		$template->pricelists = $this->pricelists;

		$template->filePricelist = $this->getFilePricelist();
		$template->simplePricelist = $this->getSimplePricelist();

		$template->setFile(dirname(__FILE__) . '/pricelists.latte');
		$template->render();

	}

	private function getSimplePricelist() {

		return isset($this->pricelists['simple']) ? array_chunk($this->pricelists['simple'], 6) : array();

	}

	private function getFilePricelist() {
		if (!isset($this->pricelists['upload'])) return array();

		$prices = array();
		foreach ($this->pricelists['upload'] as $price) {
			if ($this->currentLanguage->id === (int)$price[1]) {
				array_unshift($prices, $price);
			} else {
				$prices[] = $price;
			}
		}

		return $prices;

	}

}