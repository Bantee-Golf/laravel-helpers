<?php


namespace EMedia\Helpers\Files;


class FileManager
{

	/**
	 * Create a directory if it doesn't exist
	 *
	 * @param $dirPath
	 * @param int $permissions
	 * @param bool $recursive
	 */
	public static function mkdir_if_not_exists($dirPath, $permissions = 0777, $recursive = true)
	{
		if ( !is_dir($dirPath) ) {
			mkdir($dirPath, $permissions, $recursive);
		}
	}

}