<?php

namespace Services\Dictionary;

use Extras\Models\ServiceList;

class LanguageList extends ServiceList {


	public static function getBySupported($param) {
		$serviceList = new self;
		$serviceList->list = $serviceList->getEm()->getRepository('Entities\Dictionary\Language')
			->findBySupported($param);

		return $serviceList;
	}
}