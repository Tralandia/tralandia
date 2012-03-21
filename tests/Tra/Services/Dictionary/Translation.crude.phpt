<?php

require __DIR__ . '/../../bootstrap.php';
//die;

$service = new \Services\Dictionary\TranslationService;

$properties = \Nette\ArrayHash::from(array(
	'translation' => 'slovo',
	'translation2' => 'slova',
	'translation3' => 'slov',
));

foreach ($properties as $key => $val) {
	$service->{$key} = $val;
}


$service->save();

foreach ($properties as $key => $val) {
	Assert::same($val, $service->{$key});
}

