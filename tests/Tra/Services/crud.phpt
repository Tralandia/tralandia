<?php

require __DIR__ . '/../bootstrap.php';

$typeClass = 'Phone';
$contactPhone = '+421 948 022 044';

$type = \Services\Contact\TypeService::get();
Assert::instance('\Services\Contact\TypeService', $type);

$type->setClass($typeClass);
$type->save();

$typeId = $type->getId();
Assert::match('%d%', $typeId);

unset($type);

$contact = \Services\Contact\ContactService::get();
Assert::instance('\Services\Contact\ContactService', $contact);

$type = \Services\Contact\TypeService::get($typeId);
Assert::instance('\Services\Contact\TypeService', $type);
Assert::same($typeClass, $type->getClass());


// overujem seter
$contact->setType($type);

// overujem magiu
$contact->value = $contactPhone;

$contact->save();

$contactId = $contact->getId();
Assert::match('%d%', $contactId);

unset($contact);

$contact = \Services\Contact\ContactService::get($contactId);
Assert::instance('\Services\Contact\ContactService', $contact);

$contactType = $contact->type;
Assert::instance('\Entities\Contact\Type', $contactType);
Assert::same($type->class, $contactType->getClass());

Assert::same($contactPhone, $contact->getValue());




