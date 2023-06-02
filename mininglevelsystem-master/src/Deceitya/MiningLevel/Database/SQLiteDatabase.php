<?php

declare(strict_types = 0);

namespace Deceitya\MiningLevel\Database;

use Deceitya\MiningLevel\MiningLevelSystem;
use SQLite3;
use function file_exists;
use const SQLITE3_ASSOC;
use const SQLITE3_INTEGER;
use const SQLITE3_NUM;
use const SQLITE3_OPEN_CREATE;
use const SQLITE3_OPEN_READWRITE;
use const SQLITE3_TEXT;

class SQLiteDatabase {

    private SQLite3 $db;

    public function __construct(MiningLevelSystem $plugin) {
        $file = $plugin->getDataFolder() . 'level.db';
        if (file_exists($file)) {
            $this->db = new SQLite3($file, SQLITE3_OPEN_READWRITE);
        } else {
            $this->db = new SQLite3($file, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        }
        $this->db->exec(
            'CREATE TABLE IF NOT EXISTS MINING (' .
            'NAME TEXT NOT NULL PRIMARY KEY,' .
            'LEVEL INTEGER NOT NULL,' .
            'EXP INTEGER NOT NULL,' .
            'UPEXP INTEGER NOT NULL' .
            ')',
        );
    }

    /**
     * @param integer $level
     * @param integer $exp
     * @param integer $upexp
     * @return void
     */
    public function createPlayerData(string $name, int $level, int $exp, int $upexp) {
        $stmt = $this->db->prepare('INSERT INTO MINING (NAME, LEVEL, EXP, UPEXP) VALUES (:name, :level, :exp, :upexp)');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':level', $level, SQLITE3_INTEGER);
        $stmt->bindValue(':exp', $exp, SQLITE3_INTEGER);
        $stmt->bindValue(':upexp', $upexp, SQLITE3_INTEGER);
        $stmt->execute();
    }

    /**
     * @return integer|null
     */
    public function getLevel(string $name) : ?int {
        $stmt = $this->db->prepare('SELECT LEVEL FROM MINING WHERE NAME = :name');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        if ($result === false) {
            return null;
        }
        return $result['level'];
    }

    /**
     * @param integer $level
     * @return void
     */
    public function setLevel(string $name, int $level) {
        $stmt = $this->db->prepare('UPDATE MINING SET LEVEL = :level WHERE NAME = :name');
        $stmt->bindValue(':level', $level, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->execute();
    }

    /**
     * @return integer|null
     */
    public function getExp(string $name) : ?int {
        $stmt = $this->db->prepare('SELECT EXP FROM MINING WHERE NAME = :name');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        if ($result === false) {
            return null;
        }
        return $result['exp'];
    }

    /**
     * @param integer $exp
     * @return void
     */
    public function setExp(string $name, int $exp) {
        $stmt = $this->db->prepare('UPDATE MINING SET EXP = :exp WHERE NAME = :name');
        $stmt->bindValue(':exp', $exp, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->execute();
    }

    /**
     * @param string $name
     * @return integer|null
     */
    public function getUpExp(string $name) : ?int {
        $stmt = $this->db->prepare('SELECT UPEXP FROM MINING WHERE NAME = :name');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        if ($result === false) {
            return null;
        }
        return $result['upexp'];
    }

    /**
     * @param integer $upexp
     * @return void
     */
    public function setUpExp(string $name, int $upexp) {
        $stmt = $this->db->prepare('UPDATE MINING SET UPEXP = :upexp WHERE NAME = :name');
        $stmt->bindValue(':upexp', $upexp, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->execute();
    }

    public function getData(string $name) : array {
        $stmt = $this->db->prepare('SELECT * FROM MINING WHERE NAME = :name');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_NUM);
        return $result === false ? [] : $result;
    }

    public function setData($player, int $level, int $exp, int $upexp) {
        $stmt = $this->db->prepare('UPDATE MINING SET LEVEL = :level, EXP = :exp, UPEXP = :upexp WHERE NAME = :name');
        $stmt->bindValue(':name', $player, SQLITE3_TEXT);
        $stmt->bindValue(':level', $level, SQLITE3_INTEGER);
        $stmt->bindValue(':exp', $exp, SQLITE3_INTEGER);
        $stmt->bindValue(':upexp', $upexp, SQLITE3_INTEGER);
        $stmt->execute();
    }

    public function sort() {
        $stmt = $this->db->prepare('SELECT NAME, LEVEL FROM MINING ORDER BY LEVEL DESC');
        $data = $stmt->execute();
        while ($d = $data->fetchArray(SQLITE3_ASSOC)) {
            yield $d;
        }
    }

    public function close() {
        if ($this->db instanceof SQLite3) {
            $this->db->close();
        }
    }
}
