<?php

require __DIR__ . '/../../bootstrap.php';
die;

$service = new \Services\Dictionary\TranslationService;

$properties = \Nette\ArrayHash::from(array(
	'translation' => 'slovo',
	'translation2' => 'slova',
	'translation3' => 'slov',
	'variations' => '',
	'variationsPending' => '',
));

foreach ($properties as $key => $val) {
	$service->{$key} = $val;
}


$service->save();
unset($service);

//$service = new \Services\Dictionary\TranslationService:get()

foreach ($properties as $key => $val) {
	Assert::same($val, $service->{$key});
}