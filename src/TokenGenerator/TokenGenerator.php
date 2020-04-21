<?php

namespace EMedia\Helpers\TokenGenerator;

use EMedia\Helpers\Exceptions\TokenGenerator\CodeMaxCharacterLimitExhaustedException;
use Illuminate\Database\Eloquent\Model;

class TokenGenerator
{

	public function getCodeForModel(Model $model, $dbColumn, $maxChars = 8)
	{
		// generate a code
		$minChars = 4;
		$code = null;

		// check for duplicates
		$uniqueFound = false;

		for ($x = $minChars; $x < $maxChars; $x++) {
			// loop 100k times
			for ($i = 0, $iMax = 100000; $i < $iMax; $i++) {
				$code = $this->generate($minChars, $maxChars);
				$entity = $model::where($dbColumn, $code)->first();
				if (!$entity) {
					$uniqueFound = true;
					break 2;
				}
			}
		}

		if (!$uniqueFound) {
			throw new CodeMaxCharacterLimitExhaustedException("Failed to generate a new code for the field `$dbColumn`. Limit of {$maxChars} exhausted");
		}

		return $code;
	}



	public function generate($minChars = 4, $maxChars = 8)
	{
		$foundCode = false;
		$code = null;

		do {
			$code = self::getCode($minChars, $maxChars);
			if (!$this->isOffensive($code)) {
				$foundCode = true;
			}
		} while (!$foundCode);

		return $code;
	}

	protected function getCode($minChars = 4, $maxChars = 8) {
		$chars = "2345679ACDEFGHJKMNPQRSTUVWXYZ";
		$result = "";

		for ($i = $minChars; $i < $maxChars; $i++) {
			$result .= $chars[mt_rand(0, strlen($chars)-1)];
		}

		return $result;
	}

	/**
	 *
	 * Find if a given word is offensive
	 *
	 * @param $word
	 *
	 * @return bool
	 */
	public function isOffensive($word) {
		$word = strtolower($word);

		$offensiveRegEx = implode('|', $this->getOffensiveWords());

		if (preg_match("/({$offensiveRegEx})/i", $word)) {
			return true;
		}

		return false;
	}

	protected function getOffensiveWords() {
		return [
			'fuck', 'cunt', 'lick', 'sex', 'moth', 'ass', 'cum', 'suck',
			'hole', 'dick', 'cock', 'puss', 'bitch', 'whor', 'fcu', 'hair',
			'fat', 'black', 'nigg', 'vagi', 'frea', 'shlon', 'saus',
			'bang', 'shi', 'milf', 'gilf', 'fart', 'nut', 'blow', 'tit',
			'puk', 'pak', 'hut', 'kar', 'pon', 'wes', 'ves', 'bal', 'gon', 'pai',
		];
	}

}