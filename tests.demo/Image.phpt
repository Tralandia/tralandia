<?php

use Nette\Utils\Strings;

require __DIR__ . '/../../bootstrap.php';

$name = 'Výrobca-' . Strings::random(4);
$file = createTempImg();


// vytvorim noveho vyrobcu
$brand = new AdminModule\Services\Brand;
$brand->setName($name)->save();

// vytvorim novu instranciu sevisy obrazku
$image = new AdminModule\Services\Image;

// overim ci dostavam instanciu obrazku
Assert::instance('AdminModule\Services\Image', $image);

// pridam servise obrazok
$image->setFile($file);

// overim ci pridal nazov
Assert::same($file->getName(), $image->getName());

// pridam obrazku rodica (ku komu patri)
$image->setParent($brand);

// overim ci pridal nazov
Assert::same(AdminModule\Services\Image::TYPE_BRAND, $image->getType());

// overim ulozenie, musi vracat servisu
Assert::instance('AdminModule\Services\Image', $image->save());

// overim cas pridania
Assert::equal($image->getCreated(), new Nette\DateTime);

// vymazem vsetko co som vytvoril
Assert::true($image->remove());
$brand->remove();
unlink($file->getTemporaryFile());