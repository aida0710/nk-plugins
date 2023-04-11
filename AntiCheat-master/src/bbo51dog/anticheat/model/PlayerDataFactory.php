<?php

declare(strict_types = 0);

namespace bbo51dog\anticheat\model;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use function array_key_exists;

class PlayerDataFactory {

	use SingletonTrait;

	/** @var PlayerData[] */
	private array $datas = [];

	public function createPlayerData(Player $player) : PlayerData {
		$data = new PlayerData($player);
		$this->datas[$player->getName()] = $data;
		return $data;
	}

	public function existsPlayerData(Player $player) : bool {
		return array_key_exists($player->getName(), $this->datas);
	}

	public function getPlayerData(Player $player) : PlayerData {
		return $this->datas[$player->getName()];
	}
}
