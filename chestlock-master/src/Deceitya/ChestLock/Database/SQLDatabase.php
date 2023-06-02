<?php

declare(strict_types = 0);

namespace Deceitya\ChestLock\Database;

use SQLite3;
use function count;
use function strtolower;
use function time;
use const SQLITE3_ASSOC;
use const SQLITE3_INTEGER;
use const SQLITE3_TEXT;

class SQLDatabase {

    private ?SQLite3 $db;

    public function __construct(string $folder) {
        $this->db = new SQLite3($folder . 'lock.db');
        $this->db->exec(
            'CREATE TABLE IF NOT EXISTS LOCK (' .
            'POS TEXT NOT NULL PRIMARY KEY,' .
            'TIME INTEGER NOT NULL,' .
            'PLAYER TEXT NOT NULL)',
        );
    }

    public function register(int $x, int $y, int $z, string $level, string $player) : bool {
        if ($this->isRegistered($x, $y, $z, $level)) {
            return false;
        }
        $stmt = $this->db->prepare('INSERT INTO LOCK (POS, TIME, PLAYER) VALUES (:pos, :time, :player)');
        $stmt->bindValue(':pos', "$x:$y:$z:$level", SQLITE3_TEXT);
        $stmt->bindValue(':time', time(), SQLITE3_INTEGER);
        $stmt->bindValue(':player', strtolower($player), SQLITE3_TEXT);
        $stmt->execute();
        return true;
    }

    public function isRegistered(int $x, int $y, int $z, string $level) : bool {
        return count($this->getData($x, $y, $z, $level)) > 0;
    }

    public function getData(int $x, int $y, int $z, string $level) : array {
        $stmt = $this->db->prepare('SELECT * FROM LOCK WHERE POS = :pos');
        $stmt->bindValue(':pos', "$x:$y:$z:$level", SQLITE3_TEXT);
        $data = $stmt->execute();
        $result = [];
        while ($d = $data->fetchArray(SQLITE3_ASSOC)) {
            $result[] = $d;
        }
        return count($result) > 0 ? $result[0] : [];
    }

    public function unregister(int $x, int $y, int $z, string $level) : bool {
        if (!$this->isRegistered($x, $y, $z, $level)) {
            return false;
        }
        $stmt = $this->db->prepare('DELETE FROM LOCK WHERE POS = :pos');
        $stmt->bindValue(':pos', "$x:$y:$z:$level", SQLITE3_TEXT);
        $stmt->execute();
        return true;
    }

    public function close() {
        if ($this->db !== null) {
            $this->db->close();
            $this->db = null;
        }
    }
}
