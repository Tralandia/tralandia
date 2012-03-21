<?php

use Nette\Utils\Strings;

require __DIR__ . '/../../bootstrap.php';

$name = 'Názov stránky-' . Strings::random(8);
$title = 'Titulka stránky';
$content = 'Nejaký obsah stránky. +ľščťžýáíé';


// vytvorim novu instranciu sevisy
$page = new AdminModule\Services\Page;

// overim ci dostavam instanciu servisy
Assert::instance('AdminModule\Services\Page', $page);

// nastavenie nazvu
$page->setName($name);

// overim ci nastaveny nazov aj ziskam
Assert::same($name, $page->getName());

// overim ci sa vykonalo webalizovanie
Assert::same(Strings::webalize($name), $page->getWebalized());

// nastavenie titulky
$page->setTitle($title);

// overim ci nastaveny nazov aj ziskam
Assert::same($title, $page->getTitle());

// overim ulozenie, musi vracat servisu
Assert::instance('AdminModule\Services\Page', $page->save());

// overim cas pridania
Assert::equal($page->getCreated(), new Nette\DateTime);

// overim ci ma vyrobca cislene ID
Assert::match('%d%', $page->getId());

// vytvorim novu instranciu sevisy vyrobcu
$page = new AdminModule\Services\Page($page->getId());

// overim ci dostavam instanciu servisy
Assert::instance('AdminModule\Services\Page', $page);

// overim ci je stale ulozeny nazov
Assert::same($name, $page->getName($name));

// overim ci sa ulozilo webalizovanie
Assert::same(Strings::webalize($name), $page->getWebalized());

// overim ci je stale ulozeny nazov
Assert::same($title, $page->getTitle());

// vymazem zaznam z db
Assert::true($page->totalRemove());