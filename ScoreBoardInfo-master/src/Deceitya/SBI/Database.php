<?php

declare(strict_types=1);

namespace Deceitya\SBI;

use Deceitya\SBI\mode\Mode;
use Deceitya\SBI\mode\ModeList;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use function array_key_exists;

class Database {

	private static self $instance;

	private array $cache;

	private Config $config;

	private ModeList $modeList;

	private function __construct(string $dataFile) {
		$this->config = new Config($dataFile, Config::YAML);
		$this->cache = $this->config->getAll();
		$this->modeList = new ModeList();
	}

	public static function init(string $dataFile) {
		self::$instance = new Database($dataFile);
	}

	public static function getInstance() : self {
		return self::$instance;
	}

	public function save() {
		$this->config->setAll($this->cache);
		$this->config->save();
	}

	public function getMode(Player $player) : Mode {
		return $this->modeList->get($this->cache[$player->getName()]);
	}

	public function setMode(Player $player, int $id) {
		$this->cache[$player->getName()] = $id;
	}

	public function exists(Player $player) : bool {
		return array_key_exists($player->getName(), $this->cache);
	}
}
