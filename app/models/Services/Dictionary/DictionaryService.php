<?php

namespace Tra\Services\Dictionary;

use Tra;

class DictionaryService extends \Tra\Services\BaseService { 
	// @todo: toto musi by serviceList!
	const MAIN_ENTITY_NAME = '\Entities\Dictionary\Phrase';

	public function getPendingTranslations(Language $language) {

	}

	public function activateTranslations(Language $language) {

	}

	public function createPhrase($data) {
		$phrase = new PhraseService;
		foreach ($data as $key => $val) {
			$phrase->$key = $val;
		}
		$phrase->save();
		return $phrase;
	}

	public function createQuality($name, $value) {
/*
		$quality = new QualityService;
		$quality->name = $name;
		$quality->value = $value;
		$quality->save();

		return $quality;
*/
	}

	public function createType($data) {
/*
		$type = new \Entities\Dictionary\Type;
		foreach ($data as $key => $val) {
			if($val instanceof \Tra\Services\Service) {
				$val = $val->getMainEntity();
				//debug($val);
			}
			$type->$key = $val;
		}
		$this->em->persist($type);
		$this->em->flush();
		return $type;
*/
	}

	public function getType($id) {
		return $this->em->find('\Entities\Dictionary\Type', $id);
	}
}