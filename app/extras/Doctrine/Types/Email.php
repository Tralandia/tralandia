<?php

namespace Doctrine\Types;

use Doctrine\DBAL\Types\Type, 
	Doctrine\DBAL\Platforms\AbstractPlatform,
	Nette\Utils as NU;

class Email extends Type {
	const NAME = 'email';

	public function getName() {
		return self::NAME;
	}

	public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
		return 'varchar(255)';
	}

	public function convertToPHPValue($value, AbstractPlatform $platform) {
		return $value;
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform) {
		return $value;
	}
/*
	public function canRequireSQLConversion() {
		return true;
	}

	public function convertToPHPValueSQL($sqlExpr, AbstractPlatform $platform) {
		return sprintf('AsText(%s)', $sqlExpr);
	}

	public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform) {
		return sprintf('PointFromText(%s)', $sqlExpr);
	}
*/
}