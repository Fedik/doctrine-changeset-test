<?php
/**
 * @see http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/advanced-field-value-conversion-using-custom-mapping-types.html
 */

namespace Testing;

class Point
{
	/**
	 * @param float $latitude
	 * @param float $longitude
	 */
	public function __construct($latitude, $longitude)
	{
		$this->latitude  = (float) $latitude;
		$this->longitude = (float) $longitude;
	}

	/**
	 * @return float
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}

	/**
	 * @return float
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}

	public function __toString() {
		return $this->latitude . ',' . $this->longitude;
	}
}
