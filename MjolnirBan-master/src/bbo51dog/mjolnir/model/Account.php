<?php

declare(strict_types = 0);
namespace bbo51dog\mjolnir\model;

use pocketmine\player\Player;
use function strtolower;

class Account {

	private string $name;

	private string $ip;

	private int $cid;

	private string $xuid;

	public function __construct(string $name, string $ip, int $cid, string $xuid) {
		$this->name = strtolower($name);
		$this->ip = $ip;
		$this->cid = $cid;
		$this->xuid = $xuid;
	}

	public static function createFromPlayer(Player $player) : self {
		return new Account($player->getName(), $player->getNetworkSession()->getIp(), $player->getPlayerInfo()->getExtraData()['ClientRandomId'], $player->getXuid());
	}

	public function getName() : string {
		return $this->name;
	}

	public function getIp() : string {
		return $this->ip;
	}

	public function getCid() : int {
		return $this->cid;
	}

	public function getXuid() : string {
		return $this->xuid;
	}

}
