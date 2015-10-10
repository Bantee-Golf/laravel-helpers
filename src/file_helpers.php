<?php

if (!function_exists('mkdir_if_not_exists'))
{
	/**
	 * Create a directory if it doesn't exist
	 *
	 * @param $dirPath
	 * @param int $permissions
	 * @param bool $recursive
	 */
	function mkdir_if_not_exists($dirPath, $permissions = 0777, $recursive = true)
	{
		if ( !is_dir($dirPath) ) {
			mkdir($dirPath, $permissions, $recursive);
		}
	}
}