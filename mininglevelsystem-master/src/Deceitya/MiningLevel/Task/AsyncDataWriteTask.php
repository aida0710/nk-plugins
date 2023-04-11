<?php

declare(strict_types = 0);

namespace Deceitya\MiningLevel\Task;

use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use SQLite3;
use function file_exists;
use function serialize;
use function unserialize;
use const SQLITE3_INTEGER;
use const SQLITE3_OPEN_CREATE;
use const SQLITE3_OPEN_READWRITE;
use const SQLITE3_TEXT;

class AsyncDataWriteTask extends AsyncTask {

	public const TYPE_LEVEL = 1;
	public const TYPE_EXP = 2;
	public const TYPE_UPEXP = 3;

	public string $cache;
	public string $databasefile;
	public string $name;

	public function __construct(array $cache, string $databasefile, ?string $name = null) {
		$this->cache = serialize($cache);
		$this->databasefile = $databasefile;
		$this->name = serialize($name);
	}

	public function onRun() : void {
		$cache = unserialize($this->cache);
		$databasefile = $this->databasefile;
		if (file_exists($databasefile)) {
			$db = new SQLite3($databasefile, SQLITE3_OPEN_READWRITE);
		} else {
			$db = new SQLite3($databasefile, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
		}
		$db->exec(
			'CREATE TABLE IF NOT EXISTS mining (' .
			'name TEXT NOT NULL PRIMARY KEY,' .
			'level INTEGER NOT NULL,' .
			'exp INTEGER NOT NULL,' .
			'upexp INTEGER NOT NULL' .
			')',
		);
		foreach ($cache as $name => $data) {
			$stmt = $db->prepare('UPDATE mining SET level = :level, exp = :exp, upexp = :upexp WHERE name = :name');
			$stmt->bindValue(':level', $data[self::TYPE_LEVEL], SQLITE3_INTEGER);
			$stmt->bindValue(':exp', $data[self::TYPE_EXP], SQLITE3_INTEGER);
			$stmt->bindValue(':upexp', $data[self::TYPE_UPEXP], SQLITE3_INTEGER);
			$stmt->bindValue(':name', $name, SQLITE3_TEXT);
			$stmt->execute();
		}
		$db->close();
	}

	public function onCompletion() : void {
		$name = unserialize($this->name);
		if ($name !== null) {
			if ($name === 'CONSOLE') {
				Server::getInstance()->getLogger()->info('§bLevel §7>> §a保存は完了致しました。');
				return;
			}
			$player = Server::getInstance()->getOfflinePlayer($name);
			if ($player instanceof Player) {
				SendMessage::Send($player, '保存は完了致しました。', 'Level', true);
			}
		}
	}
}
