<?php

namespace ree_jp\bank\sqlite;

use ree_jp\bank\BankPlugin;
use ree_jp\bank\result\ResultLog;
use SQLite3;

class LogHelper implements ILogHelper {

    /**
     * @var LogHelper
     */
    private static $instance;

    /**
     * @var SQLite3
     */
    private $db;

    public function __construct(string $path) {
        $this->db = new SQLite3($path);
    }

    /**
     * @inheritDoc
     */
    static function getInstance(): LogHelper {
        if (self::$instance === null) self::$instance = new LogHelper(BankPlugin::getInstance()->getDataFolder() . 'log.db');
        return self::$instance;
    }

    /**
     * @inheritDoc
     */
    function getLog(string $bank): ResultLog {
        if (!$this->isExists($bank)) return null;
        $result = $this->db->query("SELECT * FROM [${bank}]");
        $logs = [];
        while ($array = $result->fetchArray(SQLITE3_ASSOC)) $logs[] = $array;
        return ResultLog::createResult($bank, $logs);
    }

    /**
     * @inheritDoc
     */
    function isExists(string $bank): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM sqlite_master WHERE type = 'table' AND name = :bank");
        $stmt->bindParam(':bank', $bank, SQLITE3_TEXT);
        return current($stmt->execute()->fetchArray(SQLITE3_NUM));
    }

    /**
     * @inheritDoc
     */
    function addLog(string $bank, string $message): void {
        if (!$this->isExists($bank)) $this->setTable($bank);
        $time = time();
        $stmt = $this->db->prepare("INSERT INTO [${bank}] VALUES (:time, :message)");
        $stmt->bindValue(":time", $time, SQLITE3_INTEGER);
        $stmt->bindParam(":message", $message, SQLITE3_TEXT);
        $stmt->execute();
    }

    /**
     * @param string $bank
     */
    private function setTable(string $bank): void {
        $this->db->exec("CREATE TABLE IF NOT EXISTS [${bank}] (time INTEGER NOT NULL , message TEXT NOT NULL)");
    }
}