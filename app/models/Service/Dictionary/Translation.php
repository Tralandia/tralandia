<?php

namespace Service\Dictionary;

use Entity;
use Nette\Utils\Strings;
use Services\Autopilot\Autopilot;


class Translation extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\Dictionary\Translation';

	/**
	 *	Tato metoda sa bude pouzivat ak niekto prelozil preklad (namiesto ->save())
	 */
	public function translated(\Nette\DateTime $timeTranslated = NULL) {
		$this->timeTranslated = $timeTranslated ? : new \Nette\DateTime;
		# @todo Autopilot::addTask();
		$this->save();
	}

	public function setTranslation($translation) {
		$this->getMainEntity()->variations['translation'] = $translation;
		$this->getMainEntity()->translation = $translation;
	}

 
	public function save() {
		$this->setWebalizedTexts();
		parent::save();
	}
	

	protected function setWebalizedTexts() {
		$type = $this->phrase->type;
		if($type instanceof Entity\BaseEntity) {
			if($type->webalizedRequired === TRUE) {
				$webalized = $this->webalize($this->getMainEntity()->translation);
				$this->variations = array_merge($this->variations, array('webalized' => $webalized));
			}
		} else {
			throw new \Nette\InvalidArgumentException('Pred ulozenim frázy musíš nastaviť jej typ.');
		}
	}

	protected function webalize($s) {
		return Strings::webalize($s);
	}

	public function __toString() {
		return $this->translation;
	}
	
}
