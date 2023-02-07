<?php

declare(strict_types = 1);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome\utils;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\OreType;

final class OreTypeHolder {

	public OreType $type;

	public int $value;

	public function __construct(OreType $type, int $value) {
		$this->type = $type;
		$this->value = $value;
	}
}
