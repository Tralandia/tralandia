<?php


require __DIR__ . '/../bootstrap.php';


// vyrvorim novy jazyk
$language = new Tra\Services\LanguageService;
Assert::instance('BaseEntity', $language->getMainEntity());

// naplnim jazyk a ulozim do DB
$language->iso = 'sk';
$language->supported = true;
$language->save();

// skusim ziskat jazyk z DB
$language = new Tra\Services\LanguageService($language->id);
Assert::instance('BaseEntity', $language->getMainEntity());

// overim ci je podporovany jazyk
Assert::true($language->isSupported());

// vymazem jazyk
$language->delete();