<?php

namespace Doctrine\Types;

use Doctrine\DBAL\Types\Type, 
	Doctrine\DBAL\Platforms\AbstractPlatform,
	Nette\Utils as NU;

class Slug extends Type {
	const NAME = 'slug';

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
		return NU\Strings::truncate(NU\Strings::webalize($value), 255, '');
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