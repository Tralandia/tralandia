<?php

use Nette\Utils\Strings;

require __DIR__ . '/../../bootstrap.php';
exit;
$categryName1 = 'Kategória-' . Strings::random(4);
$categryName2 = 'Kategória-' . Strings::random(4);


// vytvorim novu instranciu sevisy vyrobcu
$category1 = new AdminModule\Services\Category;

$category1->setName($categryName1);

$category1->setPosition(AdminModule\Services\Category::LAST);

$category1->setPosition(AdminModule\Services\Category::LAST);

$category1->setChild($category2);