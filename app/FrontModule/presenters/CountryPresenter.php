<?php

namespace FrontModule;

class CountryPresenter extends BasePresenter {
	
	public function actionList() {

		$continentsType = \Service\Location\Type::getBySlug('continent');
		$countriesType = \Service\Location\Type::getBySlug('country');

		$continents = \Nette\ArrayHash::from(array());
		foreach (\Service\Location\LocationList::getByType($continentsType) as $key=>$continent) {
			$countries[$key] = \Nette\ArrayHash::from(array());
			foreach (\Service\Location\LocationList::getBy(array('type'=>$countriesType, 'parentId'=>$continent)) as $key2=>$country) {
				$countries[$key][$key2] = $country;
			}
			if (count($countries[$key])) $continents[$key] = $continent;
		}

		$this->template->continents = $continents;
		$this->template->countries = $countries;

	}

	public function renderDefault() {

		

	}

}
