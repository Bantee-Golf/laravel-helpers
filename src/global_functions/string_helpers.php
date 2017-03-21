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

if (!function_exists('str_with_trailing_slash')) {
	/**
	 *
	 * Add a trailing slash to the end of a string (if it doesn't exist)
	 *
	 * @param $path
	 *
	 * @return string
	 */
	function str_with_trailing_slash($path)
	{
		return rtrim($path, '/') . '/';
	}
}

if (!function_exists('str_without_trailing_slash')) {
	/**
	 *
	 * Remove the trailing slash from a string (if exists).
	 *
	 * @param $path
	 *
	 * @return string
	 */
	function str_without_trailing_slash($path)
	{
		return rtrim($path, '/');
	}
}

if (!function_exists('str_without_leading_slash')) {
	/**
	 *
	 * Remove the leading slash of a string (if exists).
	 *
	 * @param $path
	 *
	 * @return string
	 */
	function str_without_leading_slash($path)
	{
		return trim($path, '/');
	}
}
