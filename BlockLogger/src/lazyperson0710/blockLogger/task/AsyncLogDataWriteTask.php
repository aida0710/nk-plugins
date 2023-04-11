<?php

declare(strict_types = 0);

namespace lazyperson0710\blockLogger\task;

use Exception;
use lazyperson0710\blockLogger\event\PlayerEvent;
use lazyperson0710\blockLogger\Main;
use pocketmine\scheduler\AsyncTask;
use SQLite3;
use function file_exists;
use function microtime;
use function serialize;
use function sprintf;
use function unserialize;
use const SQLITE3_OPEN_CREATE;
use const SQLITE3_OPEN_READWRITE;

class AsyncLogDataWriteTask extends AsyncTask {

	public string $cache;
	public string $dataBaseFile;
	public string $name;
	public array $error;

	private $time;

	public function __construct($time) {
		$this->time = $time;
		$this->error = [];
		$this->cache = serialize(PlayerEvent::getInstance()->getBlockLogTemp());
		PlayerEvent::getInstance()->refreshBlockLogTemp();
		$this->dataBaseFile = Main::getInstance()->getDataFolder() . 'log.db';
	}

	public function onRun() : void {
		$cache = unserialize($this->cache);
		$dataBaseFile = $this->dataBaseFile;
		if (file_exists($dataBaseFile)) {
			$db = new SQLite3($dataBaseFile, SQLITE3_OPEN_READWRITE);
		} else {
			$db = new SQLite3($dataBaseFile, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
		}
		$db->exec('begin');
		try {
			foreach ($cache as $data) {
				$db->query("INSERT INTO log VALUES(\"$data[name]\",  \"$data[type]\", \"$data[world]\", \"$data[x]\",\"$data[y]\",\"$data[z]\",\"$data[blockName]\",\"$data[blockId]\",\"$data[blockMeta]\",\"$data[others]\",\"$data[date]\",\"$data[time]\")");
			}
			$db->exec('commit');
			$db->close();
			return;
		} catch (Exception $e) {
			$this->error[] .= unserialize($e->getMessage());
			$db->exec('rollback');
			$db->close();
			return;
		}
	}

	public function onCompletion() : void {
		if (!empty($this->error)) {
			foreach ($this->error as $error) {
				Main::getInstance()->getLogger()->error('BlockLogger -> Log data save error: ' . $error);
			}
		}
		$totalTime = microtime(true) - $this->time;
		$totalTime = sprintf('%.7f', $totalTime);
		//echo "合計出力時間 : {$totalTime}\n";
		//echo "保存は完了致しました\n";
	}
}
