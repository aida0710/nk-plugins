<?php

declare(strict_types = 0);

namespace lazyperson0710\blockLogger\database;

use SQLite3;
use const SQLITE3_OPEN_CREATE;
use const SQLITE3_OPEN_READWRITE;

class DataBase extends SQLite3 {

	public function __construct(string $path) {
		parent::__construct("{$path}log.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
		$this->query('CREATE TABLE IF NOT EXISTS log (Player TEXT, action TEXT, world TEXT, X INT, Y INT, Z INT, BlockName TEXT, BlockID INT, BlockMeta INT,Others TEXT, Date TEXT, Time TEXT)');
	}
}
