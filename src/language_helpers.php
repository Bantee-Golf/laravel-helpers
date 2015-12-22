<?php

// Helpers for PHP language


if ( !function_exists('unless') )
{
	/**
	 * Negative if
	 *
	 * @param $var
	 * @return bool
	 */
	function unless($var)
	{
		if (!$var) return true;

		return false;
	}
}