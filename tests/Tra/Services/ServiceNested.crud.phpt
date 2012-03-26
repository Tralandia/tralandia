<?php

require __DIR__ . '/../bootstrap.php';

$slugRoot = 'root blabla';
$slug1 = 'child 1-1 of blabla';
$slug2 = 'child 1-2 of blabla';

$service = Services\Location\LocationService::get();
$service->slug = $slugRoot; 
$service->createRoot();

$child1 = Services\Location\LocationService::get();
$child1->slug = $slug1;
$service->addChild($child1);

$child2 = Services\Location\LocationService::get();
$child2->slug = $slug2;
$service->addChild($child2);

$serviceNode = $service->getNestedNode();

$serviceChildren = $serviceNode->getChildren();

$ch1Temp = array_shift($serviceChildren);
$ch1 = Services\Location\LocationService::get($ch1Temp->getNode());
$ch2Temp = array_shift($serviceChildren);
$ch2 = Services\Location\LocationService::get($ch2Temp->getNode());

Assert::same($slug1, $ch1->slug);
Assert::same($slug2, $ch2->slug);
