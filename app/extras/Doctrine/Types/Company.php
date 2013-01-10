<?php

namespace Doctrine\Types;

use Doctrine\DBAL\Types\Type, 
	Doctrine\DBAL\Platforms\AbstractPlatform,
	Nette\Utils as NU, Extras;

class Company extends Type {
	const NAME = 'company';

	public function getName() {
		return self::NAME;
	}

	public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
		return 'longtext';
	}

	public function convertToPHPValue($value, AbstractPlatform $platform) {
		$t = Extras\Types\Company::setFromJson($value);
		return $t; 

	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform) {
		if ($value instanceof Extras\Types\Company) {
			return $value->getAsJson();
		}
		return $value;
	}
}