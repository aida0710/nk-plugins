<?php

declare(strict_types = 0);
namespace Deceitya\MyWarp;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use SQLite3;
use function date;
use const SQLITE3_ASSOC;
use const SQLITE3_INTEGER;
use const SQLITE3_TEXT;

class Database {

	private static Database $instance;
	private SQLite3 $db;

	private function __construct() {
	}

	public function open(MyWarpPlugin $plugin) {
		$file = $plugin->getDataFolder() . 'warp.db';
		$this->db = new SQLite3($file);
		$this->db->exec(
			'CREATE TABLE IF NOT EXISTS warp (
                name TEXT NOT NULL,
                x INTEGER NOT NULL,
                y INTEGER NOT NULL,
                z INTEGER NOT NULL,
                world TEXT NOT NULL,
                created_at TEXT NOT NULL,
                created_by TEXT NOT NULL
            );',
		);
	}

	public function getWarpPMMPPosition(Player $player, string $name) : ?Position {
		$stmt = $this->db->prepare('SELECT * FROM warp WHERE created_by = :created_by AND name = :name;');
		$stmt->bindValue(':created_by', $player->getName(), SQLITE3_TEXT);
		$stmt->bindValue(':name', $name, SQLITE3_TEXT);
		$result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
		return Server::getInstance()->getWorldManager()->loadWorld($result['world']) ?
			new Position($result['x'], $result['y'], $result['z'], Server::getInstance()->getWorldManager()->getWorldByName($result['world'])) :
			null;
	}

	public static function getInstance() : self {
		if (!isset(self::$instance)) {
			self::$instance = new Database();
		}
		return self::$instance;
	}

	public function getWarpPosition(Player $player, string $name) : array {
		$stmt = $this->db->prepare('SELECT * FROM warp WHERE created_by = :created_by AND name = :name;');
		$stmt->bindValue(':created_by', $player->getName(), SQLITE3_TEXT);
		$stmt->bindValue(':name', $name, SQLITE3_TEXT);
		return $stmt->execute()->fetchArray(SQLITE3_ASSOC);
	}

	public function getWarpPositions(Player $player) : array {
		$stmt = $this->db->prepare('SELECT * FROM warp WHERE created_by = :created_by;');
		$stmt->bindValue(':created_by', $player->getName(), SQLITE3_TEXT);
		$data = $stmt->execute();
		$result = [];
		while ($d = $data->fetchArray(SQLITE3_ASSOC)) {
			$result[] = $d;
		}
		return $result;
	}

	public function createWarpPosition(Player $player, string $name) {
		$pos = $player->getPosition();
		$stmt = $this->db->prepare(
			'INSERT INTO warp (
                name, x, y, z, world, created_at, created_by
            ) VALUES (
                :name, :x, :y, :z, :world, :created_at, :created_by
            );',
		);
		$stmt->bindValue(':name', $name, SQLITE3_TEXT);
		$stmt->bindValue(':x', $pos->getFloorX(), SQLITE3_INTEGER);
		$stmt->bindValue(':y', $pos->getFloorY(), SQLITE3_INTEGER);
		$stmt->bindValue(':z', $pos->getFloorZ(), SQLITE3_INTEGER);
		$stmt->bindValue(':world', $pos->getWorld()->getFolderName(), SQLITE3_TEXT);
		$stmt->bindValue(':created_at', date('Y年m月d日 H時i分s秒'), SQLITE3_TEXT);
		$stmt->bindValue(':created_by', $player->getName(), SQLITE3_TEXT);
		$stmt->execute();
	}

	public function removeWarpPosition(Player $player, string $name) {
		$stmt = $this->db->prepare('DELETE FROM warp WHERE name = :name AND created_by = :created_by;');
		$stmt->bindValue(':name', $name, SQLITE3_TEXT);
		$stmt->bindValue(':created_by', $player->getName(), SQLITE3_TEXT);
		$stmt->execute();
	}
}
