<?php

declare(strict_types=1);
namespace Deceitya\SBI\mode;

use pocketmine\player\Player;

class NoMode implements Mode {

	public function getLines(Player $player) : ?array {
		return null;
	}

	public function getId() : int {
		return self::NO;
	}

	public function getName() : string {
		return "削除";
	}
}
