<?php

namespace EMedia\Helpers;

class AppHelper
{

	public static function convertToDbTime($string)
	{
		return date('Y-m-d H:i:s', strtotime($string));
	}

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

	public static function blankReferer($url = '')
	{
		if (empty($url)) return false;
		return 'https://href.li/?'	. $url;
	}
	

	public static function arrayKeysByRegex($pattern, $array)
	{
		// array with meta_something keys
		// extract all those keys and combine them to a single array
		$newArray = [];
		foreach ($array as $key => $value)
		{
			if (preg_match($pattern, $key)) $newArray[$key] = $value;
		}
		return $newArray;
	}


	/**
	 * Build a new array by extracting some paramters form an array of objects
	 * The count of newKeys must match the count of existingKeys.
	 *
	 * @param $objArray			Array of objects
	 * @param $newKeys			New keys that should be on the output
	 * @param $existingKeys		Existing property names on the input objects
	 * @return array|bool
	 */
	public static function extractFields($objArray, $newKeys, $existingKeys)
	{
		$newData = [];

		if (count($newKeys) !== count($existingKeys)) return false;

		foreach ($objArray as $obj)
		{
			$convertedData = [];
			$i = 0;
			foreach ($newKeys as $key)
			{
				$convertedData[$key] = $obj->$existingKeys[$i];
				$i++;
			}
			$newData[] = $convertedData;
		}

		return $newData;
	}

	public static function arrayReplaceKey($array, $currentKey, $replaceKey)
	{
		if (array_key_exists($currentKey, $array))
		{
			$nonReplacingValues		= array_except($array, $currentKey);

			$nonReplacingValues[$replaceKey] = $array[$currentKey];
			return $nonReplacingValues;
		}
		return $array;
	}


	/*
	|--------------------------------------------------------------------------
	| Routing
	|--------------------------------------------------------------------------
	|
	| Routing and URL helpers
	|
	*/



}