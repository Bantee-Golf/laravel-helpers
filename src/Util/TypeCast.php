<?php


namespace EMedia\Helpers\Util;


class TypeCast
{


	/**
	 * Converts a string with numbers to a full number
	 *
	 * @param $string
	 * @return float
	 *
	 */
	public static function convertToInteger($string)
	{
		return round(preg_replace("/[^0-9.]/", "", $string));
	}

	/**
	 *
	 * Convert a string numeric to a float
	 * Eg: 1,232.12 -> becomes -> (float) 1232.12
	 *
	 * @param $value
	 *
	 * @return float
	 */
	public static function convertToFloat($value)
	{
		return (float) (filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
	}

}