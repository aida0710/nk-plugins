<?php

declare(strict_types=1);
namespace rib\tw\level;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\World as PMWorld;
use function array_diff;
use function scandir;

class World {

	private $worlds_name;

	public function getAllWorldName() : array {
		$this->update();
		return $this->worlds_name;
	}

	private function update() : void {
		$this->worlds_name = array_diff(scandir("./worlds"), [".", ".."]);
	}

	public function teleport(Player $player, string $name) : void {
		if (!$this->loadWorld($name)) {
			throw new LevelNotFoundException($name);
		}
		$world = $this->getWorld($name);
		$player->teleport($world->getSpawnLocation());
	}

	private function loadWorld(string $name) : bool {
		return Server::getInstance()->getWorldManager()->loadWorld($name);
	}

	private function getWorld(string $name) : ?PMWorld {
		return Server::getInstance()->getWorldManager()->getWorldByName($name);
	}

}
