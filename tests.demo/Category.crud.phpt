<?php

use Nette\Utils\Strings;

require __DIR__ . '/../../bootstrap.php';

$name = 'Kategória-' . Strings::random(4);

// vytvorim novu instranciu sevisy kategoria
$category = new AdminModule\Services\Category;

// overim ci dostavam instanciu servisy
Assert::instance('AdminModule\Services\Category', $category);

// nastavenie nazvu kategorie
$category->setName($name);

// overim ci nastaveny nazov aj ziskam
Assert::same($name, $category->getName($name));

// overim ci funguje automaticke webalizovanie
Assert::same(Strings::webalize($name), $category->getWebalized());

// overim ulozenie, musi vracat servisu
Assert::instance('AdminModule\Services\Category', $category->save());

// overim cas pridania
Assert::equal($category->getCreated(), new Nette\DateTime);

// overim ci ma vyrobca cislene ID
Assert::match('%d%', $category->getId());

// vytvorim novu instranciu sevisy vyrobcu
$category = new AdminModule\Services\Category($category->getId());

// overim ci dostavam instanciu servisy
Assert::instance('AdminModule\Services\Category', $category);

// overim ci je stale ulozeny nazov
Assert::equal($name, $category->getName($name));

// overim ci sa ulozilo webalizovanie
Assert::same(Strings::webalize($name), $category->getWebalized());

// vymazem zaznam z db
Assert::true($category->remove());