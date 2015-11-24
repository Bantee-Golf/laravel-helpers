<?php

// Set of common helper functions



if (!function_exists('reverse_snake_case'))
{
	/**
	 * Convert an 'existing_snake_case' to 'exsting snake case'
	 *
	 * @param $string
	 * @return string
	 */
	function reverse_snake_case($string)
	{
		$string = str_replace('_', ' ', $string);

		return $string;
	}
}

if (!function_exists('random_unambiguous'))
{
	/**
	 * Generate a random string without any ambiguous characters
	 * @param int $length
	 * @return string
	 */
	function random_unambiguous($length = 16)
	{
		$pool = '23456789abcdefghkmnpqrstuvwxyz';

		return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
	}
}

if (!function_exists('replace_array_key'))
{

	/**
	 * Replace an existing key of an array with a new one
	 * Can be done recursively
	 * 
	 * @param array $array
	 * @param $existingKey
	 * @param $newKey
	 * @param bool|false $recursive
	 * @return array
	 */
	function replace_array_key($array = [], $existingKey, $newKey, $recursive = false)
	{
		$allArrayData = [];
		foreach ($array as $item)
		{
			$arrayData = $item;
			if (array_key_exists($existingKey, $arrayData)) {
				$arrayData[$newKey] = $arrayData[$existingKey];
				unset($arrayData[$existingKey]);
			}

			// do this recursively
			if ($recursive)
			{
				if (isset($arrayData[$newKey]) && count($arrayData[$newKey]))
				{
					$arrayData[$newKey] = replace_array_key($arrayData[$newKey], $existingKey, $newKey, true);
				}
			}

			$allArrayData[] = $arrayData;
		}
		return $allArrayData;
	}
}