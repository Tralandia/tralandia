<?php

require_once __DIR__ . '/bootstrap.php';


$entity = new Entity\Currency;
$entity->setIso('EUR')
	->setExchangeRate(44.66)
	->setRounding(2);

$container->createServiceCurrency($entity)->save();

$entity = $container->model->getRepository('Entity\Currency')->find($entity->getId());
$entity->setIso('CZK')
	->setRounding(14)
	->setRounding(987);

$service = $container->createServiceCurrency($entity);
$service->save();
$service->delete();




