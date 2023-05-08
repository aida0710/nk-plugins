<?php

declare(strict_types = 0);

namespace Deceitya\MiningLevel;

use Deceitya\MiningLevel\Database\SQLiteDatabase;
use Deceitya\MiningLevel\Task\AsyncDataWriteTask;
use pocketmine\player\Player;
use pocketmine\Server;
use function array_keys;
use function in_array;
use function strtolower;

class MiningLevelAPI {

	public const TYPE_LEVEL = 1;
	public const TYPE_EXP = 2;
	public const TYPE_UPEXP = 3;
	private static MiningLevelAPI $instance;
	public array $cache = [];
	public string $databasefile;
	private SQLiteDatabase $db;

	private function __construct() {
	}

	public function init(MiningLevelSystem $plugin) {
		$this->db = new SQLiteDatabase($plugin);
		$this->databasefile = $plugin->getDataFolder() . 'level.db';
	}

	public function deinit() {
		foreach ($this->cache as $name => $data) {
			$this->setData($name, $data[self::TYPE_LEVEL], $data[self::TYPE_EXP], $data[self::TYPE_UPEXP]);
		}
		$this->db->close();
	}

	public function setData($player, int $level, int $exp, int $upexp) {
		$player = $this->convert2lower($player);
		if ($this->playerDataExists($player)) {
			$this->db->setData($player, $level, $exp, $upexp);
		}
	}

	private function convert2lower(string|Player $player) : string {
		if ($player instanceof Player) {
			$player = $player->getName();
		}
		return strtolower($player);
	}

	/**
	 * @param Player|string $player
	 * @return boolean
	 */
	public function playerDataExists(Player|string $player) : bool {
		return !($this->getLevel($player) == null);
	}

	/**
	 * @param Player|string $player
	 * @return integer|null
	 */
	public function getLevel(Player|string $player) : ?int {
		$player = $this->convert2lower($player);
		if (!$this->isLoadeddata($player)) $this->loaddata($player);
		return $this->cache[$player][self::TYPE_LEVEL] ?? null;
	}

	public function isLoadeddata($player) : bool {
		$player = $this->convert2lower($player);
		return isset($this->cache[$player]);
	}

	public function loaddata($player) {
		$player = $this->convert2lower($player);
		$this->cache[$player] = $this->db->getData($player);
	}

	/**
	 * @param [type] $player
	 */
	public function getData($player) : array {
		$player = $this->convert2lower($player);
		if (!$this->isLoadeddata($player)) $this->loaddata($player);
		return $this->cache[$player];
	}

	/**
	 * @param string|Player $player
	 * @return void
	 */
	public function createPlayerData(Player|string $player) : void {
		$player = $this->convert2lower($player);
		if (!$this->playerDataExists($player)) {
			$this->db->createPlayerData($player, 1, 0, 80);
			$this->cache[$player][self::TYPE_LEVEL] = 1;
			$this->cache[$player][self::TYPE_EXP] = 0;
			$this->cache[$player][self::TYPE_UPEXP] = 80;
		}
	}

	/**
	 * @param string|Player $player
	 * @param integer       $level
	 * @return void
	 */
	public function setLevel(string|Player $player, int $level) : void {
		$player = $this->convert2lower($player);
		if (!$this->isLoadeddata($player)) $this->loaddata($player);
		if ($this->playerDataExists($player)) {
			$this->cache[$player][self::TYPE_LEVEL] = $level;
			//$this->db->setLevel($player, $level);
		}
	}

	/**
	 * @param string|Player $player
	 * @return integer|null
	 */
	public function getExp(string|Player $player) : ?int {
		$player = $this->convert2lower($player);
		if (!$this->isLoadeddata($player)) $this->loaddata($player);
		return $this->cache[$player][self::TYPE_EXP] ?? null;
	}

	/**
	 * @param string|Player $player
	 * @param integer       $exp
	 * @return void
	 */
	public function setExp(string|Player $player, int $exp) {
		$player = $this->convert2lower($player);
		if (!$this->isLoadeddata($player)) $this->loaddata($player);
		if ($this->playerDataExists($player)) {
			$this->cache[$player][self::TYPE_EXP] = $exp;
			//$this->db->setExp($player, $exp);
		}
	}

	/**
	 * @param string|Player $player
	 * @return integer|null
	 */
	public function getLevelUpExp(string|Player $player) : ?int {
		$player = $this->convert2lower($player);
		if (!$this->isLoadeddata($player)) $this->loaddata($player);
		return $this->cache[$player][self::TYPE_UPEXP] ?? null;
	}

	/**
	 * @param string|Player $player
	 * @param integer       $upexp
	 * @return void
	 */
	public function setLevelUpExp(string|Player $player, int $upexp) : void {
		$player = $this->convert2lower($player);
		if ($this->playerDataExists($player)) {
			if (!$this->isLoadeddata($player)) $this->loaddata($player);
			$this->cache[$player][self::TYPE_UPEXP] = $upexp;
			//$this->db->setUpExp($player, $upexp);
		}
	}

	public function genRanking() : array {//常に同期読み込みにてございます...
		$list = [];
		$i = 0;
		$ops = array_keys(Server::getInstance()->getOps()->getAll());
		foreach ($this->db->sort() as $r) {
			if (in_array($r['name'], $ops, true)) {
				continue;
			}
			$r['rank'] = $i + 1;
			if (isset($list[$i - 1]) && $list[$i - 1]['level'] === $r['level']) {
				$r['rank'] = $list[$i - 1]['rank'];
			}
			$list[$i] = $r;
			$i++;
			if ($i === 31) {
				return $list;
			}
		}
		return $list;
	}

	public static function getInstance() : MiningLevelAPI {
		if (!isset(self::$instance)) {
			self::$instance = new MiningLevelAPI();
		}
		return self::$instance;
	}

	public function writecache(?string $name = null) : void {
		$asyncexecutor = new AsyncDataWriteTask($this->cache, $this->databasefile, $name);
		Server::getInstance()->getAsyncPool()->submitTask($asyncexecutor);
	}

	public function clearCache($player) : void {
		$player = $this->convert2lower($player);
		unset($this->cache[$player]);
	}
}
