<?php


require __DIR__ . '/../bootstrap.php';


$service = Services\CurrencyService::get();
Assert::instance('Services\CurrencyService', $service);

$service->iso = 'SK';

exit;


// vyrvorim novy jazyk
$language = Services\LanguageService::get();
Assert::instance('BaseEntity', $language->getMainEntity());

// naplnim jazyk a ulozim do DB
$language->iso = 'sk';
$language->supported = true;
$language->save();

// skusim ziskat jazyk z DB
$language = Services\LanguageService::get($language->id);
Assert::instance('BaseEntity', $language->getMainEntity());

// overim ci je podporovany jazyk
Assert::true($language->isSupported());

// vymazem jazyk
$language->delete();