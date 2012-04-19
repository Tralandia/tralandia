<?php

require __DIR__ . '/../bootstrap.php';


$service = \Services\CurrencyService::get();
$service->iso = \Nette\Utils\Strings::random(5, 'A-Z');
$service->exchangeRate = 1;
$service->decimalPlaces = 1;
$service->rounding = 'r';
$service->created = new \Nette\DateTime;
$service->updated = new \Nette\DateTime;
$service->save();


$a = \Services\CurrencyService::get($service->id);
$b = \Services\CurrencyService::get($service->getMainEntity());

Assert::same($a, $b);

$service = \Services\CurrencyService::get();
$service->iso = \Nette\Utils\Strings::random(5, 'A-Z');
$service->exchangeRate = 1;
$service->decimalPlaces = 1;
$service->rounding = 'r';
$service->created = new \Nette\DateTime;
$service->updated = new \Nette\DateTime;
$service->save();

$serviceId = $service->getId();

$a = \Services\CurrencyService::get($serviceId);
$b = \Services\CurrencyService::get($serviceId);

Assert::same($a, $b);

$a = \Services\CurrencyService::get();
$b = \Services\CurrencyService::get();

MyAssert::different($a, $b);

