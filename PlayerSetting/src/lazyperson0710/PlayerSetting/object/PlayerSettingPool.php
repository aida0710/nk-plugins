<?php

declare(strict_types=1);
namespace lazyperson0710\PlayerSetting\object;

use InvalidArgumentException;
use LogicException;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class PlayerSettingPool {

	use SingletonTrait;

	/** @var array<PlayerSetting> */
	protected array $players = [];

	public function register(PlayerSetting $player_setting) : void {
		if (isset($this->players[$player_setting->getXuid()])) throw new LogicException($player_setting->getXuid() . ' is already registered');
		$this->players[$player_setting->getXuid()] = $player_setting;
	}

	public function getSetting(Player $player) : ?PlayerSetting {
		return $this->exists($player) ? $this->players[$player->getXuid()] : null;
	}

	public function getSettingNonNull(Player $player) : PlayerSetting {
		if (!$this->exists($player)) $this->create($player);
		return $this->players[$player->getXuid()] ?? throw new LogicException($player->getXuid() . ' is already registered');
	}

	public function exists(Player $player) : bool {
		$xuid = $player->getXuid();
		if ($xuid === '') throw new InvalidArgumentException($player->getName() . ' is not signed in xbox');
		return isset($this->players[$xuid]);
	}

	public function create(Player $player) : void {
		if ($this->exists($player)) throw new LogicException($player->getXuid() . ' is already registered');
		$this->players[$player->getXuid()] = new PlayerSetting($player->getXuid());
	}

	public function clear() : void {
		$this->players = [];
	}

	public function getAll() : array {
		return $this->players;
	}
}
