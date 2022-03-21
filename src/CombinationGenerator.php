<?php

declare(strict_types=1);

namespace Baraja\Combinations;


final class CombinationGenerator
{
	/**
	 * @param mixed[][]|mixed[] $input
	 * @return string[][]
	 */
	public function generate(array $input): array
	{
		try {
			$this->validateInput($input);
		} catch (\InvalidArgumentException $e) {
			throw new \InvalidArgumentException('Invalid combination input: ' . $e->getMessage(), (int) $e->getCode(), $e);
		}
		$valueToKey = [];
		foreach ($input as $key => $values) {
			foreach ($values as $value) {
				if (isset($valueToKey[$value]) === true) {
					throw new \InvalidArgumentException(sprintf(
						'Value "%s" is not unique, because the value is used in the "%s" and "%s" key.',
						$value,
						$key,
						$valueToKey[$value],
					));
				}
				$valueToKey[$value] = $key;
			}
		}

		$return = [];
		foreach ($this->combinations(array_values($input)) as $values) {
			$item = [];
			foreach ((array) $values as $value) {
				$item[$valueToKey[$value] ?? $value] = $value;
			}
			$return[] = $item;
		}

		return $return;
	}


	/**
	 * @param mixed[][]|mixed[] $input
	 */
	public function countCombinations(array $input): int
	{
		if ($input === []) {
			return 0;
		}
		$this->validateInput($input);
		$return = 1;
		foreach ($input as $values) {
			$return *= \count($values);
		}

		return $return;
	}


	/**
	 * @param string[][] $input
	 * @return string[][]|string[]
	 */
	private function combinations(array $input, int $i = 0): array
	{
		if (isset($input[$i]) === false) {
			return [];
		}
		if ($i === 0 && count($input) === 1) {
			$emptyReturn = [];
			foreach ($input[0] ?? [] as $item) {
				$emptyReturn[] = [$item];
			}

			return $emptyReturn;
		}
		if (count($input) - 1 === $i) {
			return $input[$i];
		}

		$tmp = $this->combinations($input, $i + 1);
		$return = [];
		foreach ($input[$i] as $v) {
			foreach ($tmp as $t) {
				$return[] = is_array($t) ? array_merge([$v], $t) : [$v, $t];
			}
		}

		return $return;
	}


	/**
	 * @param mixed[][]|mixed[] $input
	 */
	private function validateInput(array $input): void
	{
		foreach ($input as $key => $values) {
			if ((is_int($key) || (is_string($key) && preg_match('/^[+-]?\d+$/', $key) === 1)) === true) {
				throw new \InvalidArgumentException('Section key must be non numeric key.');
			}
			if (\is_array($values) === false) {
				throw new \InvalidArgumentException('Section values must be array, but "' . get_debug_type($values) . '" given.');
			}
			foreach ($values as $item) {
				if (\is_string($item) === false) {
					throw new \InvalidArgumentException('Section item value must be a string, but "' . get_debug_type($item) . '" given.');
				}
			}
		}
	}
}
