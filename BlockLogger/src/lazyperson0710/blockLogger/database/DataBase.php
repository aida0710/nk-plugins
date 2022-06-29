<?php

declare(strict_types=1);
namespace lazyperson0710\blockLogger\database;

use SQLite3;

class DataBase extends SQLite3 {

    public function __construct(string $path) {
        parent::__construct("{$path}log.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        $this->query("CREATE TABLE IF NOT EXISTS log (Player TEXT, action TEXT, world TEXT, X TEXT, Y TEXT, Z TEXT, BlockName TEXT, BlockID TEXT, BlockMeta TEXT,Others TEXT, Date TEXT, Time TEXT)");
    }
}