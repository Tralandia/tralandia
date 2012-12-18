<?php

namespace Service;

use Service, Doctrine, Entity;
use Entity\Language;
use Service\Robot\CreateMissingTranslationsRobot;

/**
 * @author Dávid Ďurika
 */
class LaunchLanguage extends Service\BaseService {
	
	protected $robot;
	protected $language;

	public function inject(CreateMissingTranslationsRobot $robot)
	{
		$this->robot = $robot;
	}

	public function __construct(Language $language)
	{
		$this->language = $language;
	}

	public function run()
	{
		$this->language->supported = Language::SUPPORTED;
		$this->robot->run();
		# Nemusim volat $em->flush(), spravi to robot
	}

}