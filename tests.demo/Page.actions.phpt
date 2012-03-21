<?php

use Nette\Utils\Strings;

require __DIR__ . '/../../bootstrap.php';

$name = 'Názov stránky-' . Strings::random(8);
$title = 'Titulka stránky';
$content = 'Nejaký obsah stránky. +ľščťžýáíé';


// vytvorim novu stranku
$page = new AdminModule\Services\Page;
$page->setName($name);
$page->setTitle($title);
$page->save();

// overim uvodny status
Assert::same(AdminModule\Services\Page::STATUS_DRAFT, $page->getStatus());

sleep(2);

$date = new Nette\DateTime;
$page->publish()->save();

// overim status
Assert::same(AdminModule\Services\Page::STATUS_PUBLISHED, $page->getStatus());

// overim cas publikovania
Assert::equal($page->getPublished(), $date);

$page->hidden()->save();

// overim status
Assert::same(AdminModule\Services\Page::STATUS_HIDDEN, $page->getStatus());

// overim cas publikovania
Assert::equal($page->getPublished(), $date);

$page->draft()->save();

// overim status
Assert::same(AdminModule\Services\Page::STATUS_DRAFT, $page->getStatus());

// overim cas publikovania
Assert::equal($page->getPublished(), $date);

$page->remove();

// overim status
Assert::same(AdminModule\Services\Page::STATUS_DELETED, $page->getStatus());

// overim cas publikovania
Assert::equal($page->getPublished(), $date);

// vymazem zaznam z db
Assert::true($page->totalRemove());