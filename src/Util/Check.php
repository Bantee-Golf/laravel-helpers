<?php


namespace EMedia\Helpers\Util;


class Check
{


	/**
	 *
	 * Check if all values are empty
	 * http://stackoverflow.com/questions/4993104/using-ifempty-with-multiple-variables-not-in-an-array
	 *
	 * Eg
	 * Util::all_empty($var, $var2, $var3);
	 *
	 * @return bool
	 */
	public static function all_empty()
	{
		foreach(func_get_args() as $arg)
			if (empty($arg))
				continue;
			else
				return false;

		return true;
	}

	/**
	 *
	 * Check all values are present (i.e. Not empty)
	 *
	 * @return bool
	 */
	public static function all_present()
	{
		$args = func_get_args();
		if (func_num_args() === 1 && is_array($args[0])) {
			$args = $args[0];
		}

		foreach ($args as $arg)
			if (!empty($arg))
				continue;
			else
				return false;

		return true;
	}

}