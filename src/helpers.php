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