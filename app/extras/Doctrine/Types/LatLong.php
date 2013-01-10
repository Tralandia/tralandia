<?php

namespace Doctrine\Types;

use Doctrine\DBAL\Types\Type,
	Doctrine\DBAL\Platforms\AbstractPlatform,
	Nette\Utils as NU, Extras;

class LatLong extends Type {
	const NAME = 'latlong';

	public function getName() {
		return self::NAME;
	}

	public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
		return 'float';
	}

	public function convertToPHPValue($value, AbstractPlatform $platform) {
		return 0;
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform) {
		if ($value instanceof Extras\Types\Latlong) {
			return 0;

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