<?php

use Nette\Utils\Strings;

require __DIR__ . '/../../bootstrap.php';


exit;

$name = 'Výrobca-' . Strings::random(4);
$upload = createTempImg();

// vytvorim skusobny uploadnuty subor
$file = new Nette\Http\FileUpload($upload);

// vytvorim noveho vyrobcu
$brand = new AdminModule\Services\Brand;
$brand->setName($name)->save();

// vytvorim novu instranciu sevisy obrazku
$image = new AdminModule\Services\Image;
$image->setFile($file);
$image->convert();
$image->getThumbnailUrl('big');

// vymazem vsetko co som vytvoril
Assert::true($image->remove());
$brand->remove();
unlink($file->getTemporaryFile());