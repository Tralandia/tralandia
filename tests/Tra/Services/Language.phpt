<?php


require __DIR__ . '/../bootstrap.php';



// vyrvorim novy jazyk
$language = new Tra\Services\LanguageService;
Assert::instance('BaseEntity', $language->getMainEntity());

$language->iso = 'sk';
$language->supported = true;
$language->save();

// skusim ziskat jazyk z DB
$language = new Tra\Services\LanguageService($language->id);
Assert::instance('BaseEntity', $language->getMainEntity());

// overim ci je podporovany jazyk
Assert::true($language->isSupported());