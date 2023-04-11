<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\gacha;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class GachaRollingPlayersMap {

	use SingletonTrait;

	/** @var array<string, Player> */
	private array $players = [];

	public function register(Player $player) : void {
		$this->players[$player->getName()] = $player;
	}

	public function exists(Player $player) : bool {
		return isset($this->players[$player->getName()]);
	}

	public function unregister(Player $player) : void {
		unset($this->players[$player->getName()]);
	}
}
