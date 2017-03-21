<?php


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

if (!function_exists('array_keys_snake_case'))
{
	/**
	 * Replace keys of a given array based on a given function
	 * Based on http://stackoverflow.com/questions/1444484/how-to-convert-all-keys-in-a-multi-dimenional-array-to-snake-case
	 *
	 * @param array $mixed
	 * @param callable $keyReplaceFunction
	 * @param bool|true $recursive
	 */
	function array_keys_replace(&$mixed, callable $keyReplaceFunction, $recursive = true)
	{
		if (is_array($mixed))
		{
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
		}
		else
		{
			$newVal = preg_replace('/[A-Z]/', '_$0', $mixed);
			$newVal = strtolower($newVal);
			$newVal = ltrim($newVal, '_');
			$mixed = $newVal;
			unset($newVal);
		}
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

if (!function_exists('array_key_by')) {
	/**
	 *
	 * Get an array and key it by a given key
	 * eg:
	 * [
	 *        [ 'name' => 'john', 'age' => 45 ],
	 *        [ 'name' => 'jane', 'age', => 32 ],
	 * ]
	 *
	 * becomes
	 * [
	 *        'john' => [ 'age' => 45 ],
	 *        'jane' => [ 'age' => 32 ],
	 * ]
	 *
	 *
	 * @param $array
	 * @param $keyBy
	 *
	 * @return array
	 */
	function array_key_by($array, $keyBy)
	{
		$newValues = [];

		foreach ($array as $key => $value) {
			if (is_array($value)) {
				if (isset($value[$keyBy]) && $value[$keyBy] != '') {
					$newValues[$value[$keyBy]][] = $value;
				}
			}
		}

		return $newValues;
	}
}

if (!function_exists('implode_not_empty'))
{
	/**
	 *
	 * Implode but don't include empty values
	 *
	 * @param $glue
	 * @param $pieces
	 *
	 * @return string
	 */
	function implode_not_empty($glue, $pieces)
	{
		$pieces = array_filter($pieces);	// remove empty names
		return implode($glue, $pieces);
	}
}