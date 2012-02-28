<?php

namespace Tra\Services;

use Tra;

class DictionaryService extends BaseService {

	const MAIN_ENTITY_NAME = '\Dictionary\Phrase';

	public function getPendingTranslations(Language $language) {

	}

	public function activateTranslations(Language $language) {

	}

	public function addPhrase($data) {
		$phrase = new \Dictionary\Phrase;
		foreach ($data as $key => $val) {
			$phrase->$key = $val;
		}
		$this->em->persist($phrase);
		$this->em->flush();
		return $phrase;
	}

	public function addQuality($name, $value) {
		$quality = new \Dictionary\Quality;
		$quality->name = $name;
		$quality->value = $value;
		$this->em->persist($quality);
		$this->em->flush();
		return $quality;
	}

	public function addType($data) {
		$type = new \Dictionary\Type;
		foreach ($data as $key => $val) {
			$type->$key = $val;
		}
		$this->em->persist($type);
		$this->em->flush();
		return $type;
	}

	public function getType($id) {
		return $this->em->find('\Dictionary\Type', $id);
	}
}