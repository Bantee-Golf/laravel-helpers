<?php


use EMedia\Helpers\Util\Check;

if (!function_exists('check_all_present')) {
	/**
	 *
	 * Check all values are !empty
	 * Throws an exception if at least one value is empty
	 *
	 */
	function check_all_present()
	{
		$args = func_get_args();

		if (!Check::all_present($args)) {
			throw new InvalidArgumentException('At least one required variable is not present');
		}
	}
}