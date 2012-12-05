<?php

namespace FrontModule\Components\Footer;

interface IFooterControlFactory {
	public function create(\Entity\Location\Location $location);
}

interface ICountriesFooterControlFactory {
	public function create();
}

