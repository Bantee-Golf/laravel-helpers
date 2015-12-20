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


/**
 * Replace keys of a given array based on a given function
 * Based on http://stackoverflow.com/questions/1444484/how-to-convert-all-keys-in-a-multi-dimenional-array-to-snake-case
 *
 * @param array		$mixed
 * @param callable 	$keyReplaceFunction
 * @param bool|true $recursive
 */
function array_keys_replace(&$mixed, callable $keyReplaceFunction, $recursive = true)
{
	if (is_array($mixed)) {
		foreach (array_keys($mixed) as $key):
			# Working with references here to avoid copying the value,
			# Since input data can be large
			$value = &$mixed[$key];
			unset($mixed[$key]);

			#  - camelCase to snake_case
			$transformedKey = $keyReplaceFunction($key);

			# Work recursively
			if ($recursive && is_array($value)) array_keys_replace($value, $keyReplaceFunction, $recursive);

			# Store with new key
			$mixed[$transformedKey] = $value;
			# Do not forget to unset references!
			unset($value);
		endforeach;
	} else {
		$newVal = preg_replace('/[A-Z]/', '_$0', $mixed);
		$newVal = strtolower($newVal);
		$newVal = ltrim($newVal, '_');
		$mixed = $newVal;
		unset($newVal);
	}
}


if (!function_exists('array_keys_snake_case'))
{
	/**
	 * Convert camelCase type array keys to snake case
	 *
	 * @param array $mixed
	 * @param bool|true $recursive
	 */
	function array_keys_snake_case(&$mixed, $recursive = true)
	{
		if (!function_exists('snake_case')) throw new \Exception("Function 'snake_case' is undefined.");
		array_keys_replace($mixed, 'snake_case', $recursive);
	}
}


/**
 * Convert array keys to camelCase
 *
 * @param $mixed
 * @param bool|true $recursive
 * @throws Exception
 */
if (!function_exists('array_keys_camel_case'))
{
	function array_keys_camel_case(&$mixed, $recursive = true)
	{
		if (!function_exists('camel_case')) throw new \Exception("Function 'camel_case' is undefined.");
		array_keys_replace($mixed, 'camel_case', $recursive);
	}
}
