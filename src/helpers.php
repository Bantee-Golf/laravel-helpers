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