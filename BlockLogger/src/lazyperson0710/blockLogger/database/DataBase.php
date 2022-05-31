<?php

declare(strict_types=1);
namespace lazyperson0710\blockLogger\database;

use SQLite3;

class DataBase extends SQLite3 {

    public function __construct(string $path) {
        parent::__construct("{$path}log.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        $this->query("CREATE TABLE IF NOT EXISTS log (Player TEXT, action TEXT, world TEXT, X TEXT, Y TEXT, Z TEXT, BlockName TEXT, BlockID TEXT, BlockMeta TEXT, Date TEXT, Time TEXT)");
    }

    /*public function checkLog(Event $event, string $type): string {
        $xyz = "x{$x}y{$y}z{$z}w{$level}";
        $result = $this->query("SELECT who, action, id, meta, time FROM logdata WHERE xyz = \"$xyz\"");
        $results = $result->fetchArray();
        if (!$results) {
            return "{$x},{$y},{$z},{$level} ここにログは存在していません";
        } else {
            $pb = $results['action'] === "Break" ? "破壊" : $pb = "設置";
            $itemname = ItemFactory::getInstance()->get($results['id'], $results['meta'], 1)->getName();
            return "§c[座標] {$x},{$y},{$z},{$level}\n[日時] {$results['time']}\n[行動者] {$results['who']}\n[行動]{$pb}\n[物] {$results['id']}:{$results['meta']} {$itemname}";
        }
    }*/
}