<?php

declare(strict_types=1);

namespace Deceitya\ChestLock;

use Deceitya\ChestLock\Command\ChestCommand;
use Deceitya\ChestLock\Database\SQLDatabase;
use Deceitya\ChestLock\Event\EventListener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\world\Position;

class Main extends PluginBase {

	private SQLDatabase $db;
	/** @var int[] */
	private array $stats = [];

	public function onEnable() : void {
		$this->reloadConfig();
		$this->db = new SQLDatabase($this->getDataFolder());
		$this->getServer()->getCommandMap()->register('chestlock', new ChestCommand($this));
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
	}

	public function onDisable() : void {
		$this->db->close();
	}

	public function isChestLocked(Position $pos) : bool {
		return $this->db->isRegistered($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), $pos->getWorld()->getFolderName());
	}

	public function lockChest(Position $pos, Player $player) : bool {
		return $this->db->register($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), $pos->getWorld()->getFolderName(), $player->getName());
	}

	public function unlockChest(Position $pos) : bool {
		return $this->db->unregister($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), $pos->getWorld()->getFolderName());
	}

	public function getData(Position $pos) : array {
		return $this->db->getData($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), $pos->getWorld()->getFolderName());
	}

	public function getEnabledWorlds() : array {
		return $this->getConfig()->get('worlds');
	}

	public function getStat(Player $player) : int {
		$name = $player->getName();
		return $this->stats[$name] ?? -1;
	}

	public function setStat(Player $player, int $stat) {
		$this->stats[$player->getName()] = $stat;
	}

	public function removeStat(Player $player) {
		unset($this->stats[$player->getName()]);
	}
}
