<?php

declare(strict_types = 0);

namespace bbo51dog\announce;

use pocketmine\utils\TextFormat;

interface MessageFormat {

	public const PREFIX_PASS = TextFormat::AQUA . 'Pass ' . TextFormat::GRAY . '>> ' . TextFormat::RESET;
}
