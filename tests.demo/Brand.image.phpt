<?php

use Nette\Utils\Strings;

require __DIR__ . '/../../bootstrap.php';

$name = 'Výrobca-' . Strings::random(4);
$file = createTempImg();


// vytvorim obrazok
$image = new AdminModule\Services\Image;
$image->setFile($file);
$image->save();

// vytvorim novu instranciu sevisy vyrobcu
$brand = new AdminModule\Services\Brand;
$brand->setName($name);
$brand->setImage($image);
$brand->save();

// overujem ci mi to v oboch pripadoch vracia ten isty objekt
Assert::same($brand, $image->getParent());

// vycistim system
$brand->remove();
$image->remove();
unlink($file->getTemporaryFile());