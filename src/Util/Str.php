<?php


namespace EMedia\Helpers\Util;


class Str
{

	/**
	 *
	 * Get a block of text and split it into lines
	 * Eg:
	 * one, two,   three
	 * four
	 * five
	 *
	 * Returns
	 * ['one', 'two', 'three', 'four', 'five']
	 *
	 * @param $text
	 *
	 * @return array
	 */
	public static function textToLinesArray($text, $delimiters = null)
	{
		if (!$delimiters) $delimiters = [PHP_EOL, ';', ','];

		$lines = str_replace($delimiters, $delimiters[0], $text);

		$trimmedLines =  array_map('trim', explode(PHP_EOL, $lines));

		// remove empty values
		$lines = array_filter($trimmedLines, function ($item) {
			return $item !== '';
		});

		return $lines;
	}

}