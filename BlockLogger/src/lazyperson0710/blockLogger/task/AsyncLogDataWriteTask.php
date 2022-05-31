<?php

namespace lazyperson0710\blockLogger\task;

use lazyperson0710\blockLogger\event\PlayerEvent;
use lazyperson0710\blockLogger\Main;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use SQLite3;

class AsyncLogDataWriteTask extends AsyncTask {

    public string $cache;
    public string $dataBaseFile;
    public string $name;

    public function __construct() {
        $this->cache = serialize(PlayerEvent::getInstance()->getBlockLogTemp());
        PlayerEvent::getInstance()->refreshBlockLogTemp();
        $this->dataBaseFile = Main::getInstance()->getDataFolder() . 'log.db';
    }

    public function onRun(): void {
        $cache = unserialize($this->cache);
        $dataBaseFile = $this->dataBaseFile;
        if (file_exists($dataBaseFile)) {
            $db = new SQLite3($dataBaseFile, SQLITE3_OPEN_READWRITE);
        } else {
            $db = new SQLite3($dataBaseFile, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        }
        foreach ($cache as $data) {
            $db->query("INSERT INTO log VALUES(\"$data[name]\",  \"$data[type]\", \"$data[world]\", \"$data[x]\",\"$data[y]\",\"$data[z]\",\"$data[blockName]\",\"$data[blockId]\",\"$data[blockMeta]\",\"$data[date]\",\"$data[time]\")");
        }
        $db->close();
    }

//    public function onCompletion(): void {
//        Server::getInstance()->getLogger()->info("§bLevel §7>> §a保存は完了致しました。");
//    }
}