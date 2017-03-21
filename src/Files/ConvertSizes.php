<?php


namespace EMedia\Helpers\Files;


class ConvertSizes
{

	/**
	 *
	 * Convert bytes to a human readable format.
	 * http://codeaid.net/php/convert-size-in-bytes-to-a-human-readable-format-(php)
	 *
	 * @param int $bytes
	 * @param int $precision
	 *
	 * @return string
	 */
	public static function bytesToHumans($bytes, $precision = 2)
	{
		$kilobyte = 1024;
		$megabyte = 1048576; 		// $kilobyte * 1024;
		$gigabyte = 1073741824; 	// $megabyte * 1024;
		$terabyte = 1099511627776; 	// $gigabyte * 1024;

		if (($bytes >= 0) && ($bytes < $kilobyte)) {
			return $bytes . ' B';

		} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
			return round($bytes / $kilobyte, $precision) . ' KB';

		} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
			return round($bytes / $megabyte, $precision) . ' MB';

		} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
			return round($bytes / $gigabyte, $precision) . ' GB';

		} elseif ($bytes >= $terabyte) {
			return round($bytes / $terabyte, $precision) . ' TB';
		} else {
			return $bytes . ' B';
		}
	}

}