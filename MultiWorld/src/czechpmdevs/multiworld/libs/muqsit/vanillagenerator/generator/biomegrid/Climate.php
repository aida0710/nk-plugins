<?php

declare(strict_types = 0);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\biomegrid;

class Climate {

	public int $value;

	/** @var int[] */
	public array $crossTypes;

	public int $finalValue;

	/**
	 * @param int[] $crossTypes
	 */
	public function __construct(int $value, array $crossTypes, int $finalValue) {
		$this->value = $value;
		$this->crossTypes = $crossTypes;
		$this->finalValue = $finalValue;
	}
}
