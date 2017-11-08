<?php

/**
 * @see http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/advanced-field-value-conversion-using-custom-mapping-types.html
 */

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use Testing\Point;

class PointType extends Type
{
	const POINT = 'point';

	public function getName()
	{
		return self::POINT;
	}

	public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
	{
		return 'POINT';
	}

	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		list($longitude, $latitude) = sscanf($value, 'POINT(%f %f)');

		return new Point($latitude, $longitude);
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if ($value instanceof Point) {
			$value = sprintf('POINT(%F %F)', $value->getLongitude(), $value->getLatitude());
		}

		return $value;
	}

	public function canRequireSQLConversion()
	{
		return true;
	}

	public function convertToPHPValueSQL($sqlExpr, AbstractPlatform $platform)
	{
		return sprintf('AsText(%s)', $sqlExpr);
	}

	public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform)
	{
		return sprintf('PointFromText(%s)', $sqlExpr);
	}

	public function isValuesIdentical($val1, $val2)
	{
		return $val1 && $val2 && $val1->getLatitude() === $val2->getLatitude() && $val1->getLongitude() === $val2->getLongitude();
	}
}