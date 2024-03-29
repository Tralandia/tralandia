<?php

namespace Doctrine\Types;

use Doctrine\DBAL\Types\Type,
	Doctrine\DBAL\Platforms\AbstractPlatform,
	Nette\Utils as NU;

class Json extends Type {
	const NAME = 'json';

	public function getName() {
		return self::NAME;
	}

	public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
		return 'longtext';
	}

	public function convertToPHPValue($value, AbstractPlatform $platform) {
		try {
			$array = NU\Json::decode($value, NU\Json::FORCE_ARRAY);
		} catch(NU\JsonException $e) {
			$array = $value;
		}
		return $array;
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform) {
		if (!is_string($value)) {
			$value = @NU\Json::encode($value);
			if (!$value) debug($value);
		}
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
