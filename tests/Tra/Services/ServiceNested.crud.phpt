<?php

require __DIR__ . '/../bootstrap.php';

die;
$service = Services\Location\LocationService::get(2);
//$service->slug = 'root blabla'; $service->createRoot();

$child1 = Services\Location\LocationService::get();
$child1->slug = 'child 1-1 of blabla';
$service->addChild($child1);

$child2 = Services\Location\LocationService::get();
$child2->slug = 'child 1-2 of blabla';

$service->addChild($child2);


