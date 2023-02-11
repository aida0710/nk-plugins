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

	public function close() : void {
	}

	public function getAccountsByName(string $name) : array {
		$name = strtolower($name);
		$stmt = $this->db->prepare('SELECT * FROM accounts WHERE playerName = :playerName');
		$stmt->bindValue(':playerName', $name);
		$result = $stmt->execute();
		$accounts = [];
		for ($data = $result->fetchArray(); is_array($data); $data = $result->fetchArray()) {
			$accounts[] = new Account($data['playerName'], $data['ip'], $data['cid'], $data['xuid']);
		}
		return $accounts;
	}

	public function getAccountsByIp(string $ip) : array {
		$stmt = $this->db->prepare('SELECT * FROM accounts WHERE ip = :ip');
		$stmt->bindValue(':ip', $ip);
		$result = $stmt->execute();
		$accounts = [];
		for ($data = $result->fetchArray(); is_array($data); $data = $result->fetchArray()) {
			$accounts[] = new Account($data['playerName'], $data['ip'], $data['cid'], $data['xuid']);
		}
		return $accounts;
	}

	public function getAccountsByCid(int $cid) : array {
		$stmt = $this->db->prepare('SELECT * FROM accounts WHERE cid = :cid');
		$stmt->bindValue(':cid', $cid);
		$result = $stmt->execute();
		$accounts = [];
		for ($data = $result->fetchArray(); is_array($data); $data = $result->fetchArray()) {
			$accounts[] = new Account($data['playerName'], $data['ip'], $data['cid'], $data['xuid']);
		}
		return $accounts;
	}

	public function getAccountsByXuid(string $xuid) : array {
		$stmt = $this->db->prepare('SELECT * FROM accounts WHERE xuid = :xuid');
		$stmt->bindValue(':xuid', $xuid);
		$result = $stmt->execute();
		$accounts = [];
		for ($data = $result->fetchArray(); is_array($data); $data = $result->fetchArray()) {
			$accounts[] = new Account($data['playerName'], $data['ip'], $data['cid'], $data['xuid']);
		}
		return $accounts;
	}

	public function register(Account $account) : void {
		$stmt = $this->db->prepare('INSERT INTO accounts values(:playerName, :ip, :cid, :xuid)');
		$stmt->bindValue(':playerName', $account->getName());
		$stmt->bindValue(':ip', $account->getIp());
		$stmt->bindValue(':cid', $account->getCid());
		$stmt->bindValue(':xuid', $account->getXuid());
		$stmt->execute();
	}

	public function registerIfNotExists(Account $account) : void {
		if (!$this->exists($account)) {
			$this->register($account);
		}
	}

	public function exists(Account $account) : bool {
		$stmt = $this->db->prepare('SELECT COUNT (*) FROM accounts WHERE playerName = :playerName AND ip = :ip AND cid = :cid AND xuid = :xuid');
		$stmt->bindValue(':playerName', $account->getName());
		$stmt->bindValue(':ip', $account->getIp());
		$stmt->bindValue(':cid', $account->getCid());
		$stmt->bindValue(':xuid', $account->getXuid());
		$rows = $stmt->execute()->fetchArray();
		return $rows[0] > 0;
	}

	private function prepareTable() : void {
		$this->db->query('CREATE TABLE IF NOT EXISTS accounts(playerName TEXT, ip TEXT, cid INTEGER, xuid TEXT)');
	}
}
