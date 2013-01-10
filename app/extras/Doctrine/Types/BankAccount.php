<?php

namespace Doctrine\Types;

use Doctrine\DBAL\Types\Type, 
	Doctrine\DBAL\Platforms\AbstractPlatform,
	Nette\Utils as NU, Extras;

class BankAccount extends Type {
	const NAME = 'bankAccount';

	public function getName() {
		return self::NAME;
	}

	public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
		return 'longtext';
	}

	public function convertToPHPValue($value, AbstractPlatform $platform) {
		$t = Extras\Types\BankAccount::setFromJson($value);
		return $t; 

	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform) {
		if ($value instanceof Extras\Types\BankAccount) {
			return $value->getAsJson();
		}
		return $value;
	}
}