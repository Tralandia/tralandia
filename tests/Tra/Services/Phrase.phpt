<?php


require __DIR__ . '/../bootstrap.php';

// vyrvorim novy jazyk
$language = new Tra\Services\LanguageService;
$language->iso = 'sk';
$language->supported = true;
$language->save();

// vytvorim novu prazdnu frazu
$phrase = new Tra\Services\PhraseService;
Assert::instance('BaseEntity', $phrase->getMainEntity());

// pridam jazyk ku fraze
$phrase = $phrase->addLanguage($language);

// ziskam jazyky priradene ku fraze
foreach ($phrase->getLanguages() as $lang) {
	Assert::instance('Tra\Services\LanguageService', $lang);
}