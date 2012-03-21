<?php

use Nette\Utils\Strings;

require __DIR__ . '/../../bootstrap.php';

$name = 'Výrobca-' . Strings::random(4);


// vytvorim novu instranciu sevisy vyrobcu
$brand = new AdminModule\Services\Brand;

// overim ci dostavam instanciu servisy
Assert::instance('AdminModule\Services\Brand', $brand);

// nastavenie nazvu vyrobcovi
$brand->setName($name);

// overim ci nastaveny nazov aj ziskam
Assert::same($name, $brand->getName());

// overim ci sa vykonalo webalizovanie
Assert::same(Strings::webalize($name), $brand->getWebalized());

// overim ulozenie, musi vracat servisu
Assert::instance('AdminModule\Services\Brand', $brand->save());

// overim cas pridania
Assert::equal($brand->getCreated(), new Nette\DateTime);

// overim ci ma vyrobca cislene ID
Assert::match('%d%', $brand->getId());

// vytvorim novu instranciu sevisy vyrobcu
$brand = new AdminModule\Services\Brand($brand->getId());

// overim ci dostavam instanciu servisy
Assert::instance('AdminModule\Services\Brand', $brand);

// overim ci je stale ulozeny nazov
Assert::same($name, $brand->getName());

// overim ci sa ulozilo webalizovanie
Assert::same(Strings::webalize($name), $brand->getWebalized());

// vymazem zaznam z db
Assert::true($brand->remove());