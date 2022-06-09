<?php

namespace lazyperson0710\blockLogger\task;

use lazyperson0710\blockLogger\event\PlayerEvent;
use lazyperson0710\blockLogger\Main;
use pocketmine\scheduler\AsyncTask;
use SQLite3;

class AsyncLogDataWriteTask extends AsyncTask {

    public string $cache;
    public string $dataBaseFile;
    public string $name;

    private $time;

    public function __construct($time) {
        $this->time = $time;
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
        //$sql = "INSERT INTO log VALUES";
        //foreach ($cache as $data) {
        //    $sql .= "(\"$data[name]\",  \"$data[type]\", \"$data[world]\", \"$data[x]\",\"$data[y]\",\"$data[z]\",\"$data[blockName]\",\"$data[blockId]\",\"$data[blockMeta]\",\"$data[date]\",\"$data[time]\"),\n";
        //}
        //var_dump($sql);
        //if (empty($sql)) return;
        //$sql = mb_substr($sql, 0, -1);
        ////$sql .= ";";
        //$db->query("{$sql}");
        $db->close();
    }

    public function onCompletion(): void {
        $totalTime = microtime(true) - $this->time;
        $totalTime = sprintf("%.7f", $totalTime);
        echo "合計出力時間 : {$totalTime}\n";
        echo "保存は完了致しました\n";
    }
}