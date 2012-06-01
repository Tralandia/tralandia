<?php

namespace Doctrine\Types;

use Doctrine\DBAL\Types\Type, 
	Doctrine\DBAL\Platforms\AbstractPlatform,
	Nette\Utils as NU;

class Address extends Type {
	const NAME = 'address';

	public function getName() {
		return self::NAME;
	}

	public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
		return 'longtext';
	}

	public function convertToPHPValue($value, AbstractPlatform $platform) {
		return \Extras\Types\Address::decode($value);
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform) {
		if ($value) {
			return $value->encode();
		} else {
			return NULL;
		}
	}
}