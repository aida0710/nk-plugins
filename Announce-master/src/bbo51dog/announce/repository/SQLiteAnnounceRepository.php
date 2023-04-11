<?php

declare(strict_types = 0);

namespace bbo51dog\announce\repository;

use bbo51dog\announce\repository\dto\AnnounceDto;
use SQLite3;

class SQLiteAnnounceRepository implements AnnounceRepository {

	private SQLite3 $db;

	public function __construct(SQLite3 $db) {
		$this->db = $db;
		$this->createTable();
	}

	private function createTable() {
		$this->db->exec('CREATE TABLE IF NOT EXISTS announces (id INTEGER PRIMARY KEY AUTOINCREMENT, content TEXT NOT NULL , type INTEGER NOT NULL , timestamp INTEGER NOT NULL)');
	}

	public function getAnnounce(int $id) : ?AnnounceDto {
		$stmt = $this->db->prepare('SELECT * FROM announces WHERE id = :id');
		$stmt->bindValue(':id', $id);
		$result = $stmt->execute();
		$rows = $result->fetchArray();
		if ($rows === false) {
			return null;
		}
		return new AnnounceDto($rows['content'], $rows['type'], $rows['timestamp'], $rows['id']);
	}

	public function register(AnnounceDto $dto) : int {
		$stmt = $this->db->prepare('INSERT INTO announces(content, type, timestamp) VALUES (:content, :type, :timestamp)');
		$stmt->bindValue(':content', $dto->getContent());
		$stmt->bindValue(':type', $dto->getType());
		$stmt->bindValue(':timestamp', $dto->getTimestamp());
		$stmt->execute();
		return $this->db->lastInsertRowID();
	}

	public function exists(int $id) : bool {
		$stmt = $this->db->prepare('SELECT COUNT(*) FROM announces WHERE id = :id');
		$stmt->bindValue(':id', $id);
		$rows = $stmt->execute()->fetchArray();
		return $rows[0] > 0;
	}

	public function getLatestAnnounce() : ?AnnounceDto {
		$stmt = $this->db->prepare('SELECT * FROM announces WHERE timestamp = (SELECT max(timestamp) FROM announces)');
		$result = $stmt->execute();
		$rows = $result->fetchArray();
		if ($rows === false) {
			return null;
		}
		return new AnnounceDto($rows['content'], $rows['type'], $rows['timestamp'], $rows['id']);
	}

	public function getOldestAnnounce() : ?AnnounceDto {
		$stmt = $this->db->prepare('SELECT * FROM announces WHERE timestamp = (SELECT min(timestamp) FROM announces)');
		$result = $stmt->execute();
		$rows = $result->fetchArray();
		if ($rows === false) {
			return null;
		}
		return new AnnounceDto($rows['content'], $rows['type'], $rows['timestamp'], $rows['id']);
	}

	public function close() : void {
	}
}
