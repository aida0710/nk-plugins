<?php

declare(strict_types=1);
namespace rib\tw\level;

use UnexpectedValueException;

class LevelNotFoundException extends UnexpectedValueException {

	public function __construct(string $level) {
		parent::__construct("not found world data: {$level}");
	}

}
