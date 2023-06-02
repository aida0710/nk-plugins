<?php

declare(strict_types = 0);

namespace bbo51dog\mjolnir\repository\sqlite;

use bbo51dog\mjolnir\model\Account;
use bbo51dog\mjolnir\repository\AccountRepository;
use SQLite3;
use function is_array;
use function strtolower;

class SQLiteAccountRepository implements AccountRepository {

    private SQLite3 $db;

    public function __construct(SQLite3 $db) {
        $this->db = $db;
        $this->prepareTable();
    }

    private function prepareTable() : void {
        $this->db->query('CREATE TABLE IF NOT EXISTS ACCOUNTS(PLAYERNAME TEXT, IP TEXT, CID INTEGER, XUID TEXT)');
    }

    public function close() : void {
    }

    public function getAccountsByCid(int $cid) : array {
        $stmt = $this->db->prepare('SELECT * FROM ACCOUNTS WHERE CID = :cid');
        $stmt->bindValue(':cid', $cid);
        $result = $stmt->execute();
        $accounts = [];
        for ($data = $result->fetchArray(); is_array($data); $data = $result->fetchArray()) {
            $accounts[] = new Account($data['playerName'], $data['ip'], $data['cid'], $data['xuid']);
        }
        return $accounts;
    }

    public function getAccountsByIp(string $ip) : array {
        $stmt = $this->db->prepare('SELECT * FROM ACCOUNTS WHERE IP = :ip');
        $stmt->bindValue(':ip', $ip);
        $result = $stmt->execute();
        $accounts = [];
        for ($data = $result->fetchArray(); is_array($data); $data = $result->fetchArray()) {
            $accounts[] = new Account($data['playerName'], $data['ip'], $data['cid'], $data['xuid']);
        }
        return $accounts;
    }

    public function getAccountsByName(string $name) : array {
        $name = strtolower($name);
        $stmt = $this->db->prepare('SELECT * FROM ACCOUNTS WHERE PLAYERNAME = :playerName');
        $stmt->bindValue(':playerName', $name);
        $result = $stmt->execute();
        $accounts = [];
        for ($data = $result->fetchArray(); is_array($data); $data = $result->fetchArray()) {
            $accounts[] = new Account($data['playerName'], $data['ip'], $data['cid'], $data['xuid']);
        }
        return $accounts;
    }

    public function getAccountsByXuid(string $xuid) : array {
        $stmt = $this->db->prepare('SELECT * FROM ACCOUNTS WHERE XUID = :xuid');
        $stmt->bindValue(':xuid', $xuid);
        $result = $stmt->execute();
        $accounts = [];
        for ($data = $result->fetchArray(); is_array($data); $data = $result->fetchArray()) {
            $accounts[] = new Account($data['playerName'], $data['ip'], $data['cid'], $data['xuid']);
        }
        return $accounts;
    }

    public function registerIfNotExists(Account $account) : void {
        if (!$this->exists($account)) {
            $this->register($account);
        }
    }

    public function exists(Account $account) : bool {
        $stmt = $this->db->prepare('SELECT COUNT (*) FROM ACCOUNTS WHERE PLAYERNAME = :playerName AND IP = :ip AND CID = :cid AND XUID = :xuid');
        $stmt->bindValue(':playerName', $account->getName());
        $stmt->bindValue(':ip', $account->getIp());
        $stmt->bindValue(':cid', $account->getCid());
        $stmt->bindValue(':xuid', $account->getXuid());
        $rows = $stmt->execute()->fetchArray();
        return $rows[0] > 0;
    }

    public function register(Account $account) : void {
        $stmt = $this->db->prepare('INSERT INTO ACCOUNTS VALUES(:playerName, :ip, :cid, :xuid)');
        $stmt->bindValue(':playerName', $account->getName());
        $stmt->bindValue(':ip', $account->getIp());
        $stmt->bindValue(':cid', $account->getCid());
        $stmt->bindValue(':xuid', $account->getXuid());
        $stmt->execute();
    }
}
