<?php


require __DIR__ . '/../bootstrap.php';


$service = new Services\CurrencyService;
Assert::instance('Services\CurrencyService', $service);

$service->iso = 'SK';

exit;


// vyrvorim novy jazyk
$language = new Services\LanguageService;
Assert::instance('BaseEntity', $language->getMainEntity());

// naplnim jazyk a ulozim do DB
$language->iso = 'sk';
$language->supported = true;
$language->save();

// skusim ziskat jazyk z DB
$language = new Services\LanguageService($language->id);
Assert::instance('BaseEntity', $language->getMainEntity());

// overim ci je podporovany jazyk
Assert::true($language->isSupported());

// vymazem jazyk
$language->delete();