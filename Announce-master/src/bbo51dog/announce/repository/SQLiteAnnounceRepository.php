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

    public function close() : void {
    }

    public function exists(int $id) : bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM ANNOUNCES WHERE ID = :id');
        $stmt->bindValue(':id', $id);
        $rows = $stmt->execute()->fetchArray();
        return $rows[0] > 0;
    }

    public function getAnnounce(int $id) : ?AnnounceDto {
        $stmt = $this->db->prepare('SELECT * FROM ANNOUNCES WHERE ID = :id');
        $stmt->bindValue(':id', $id);
        $result = $stmt->execute();
        $rows = $result->fetchArray();
        if ($rows === false) {
            return null;
        }
        return new AnnounceDto($rows['content'], $rows['type'], $rows['timestamp'], $rows['id']);
    }

    public function getLatestAnnounce() : ?AnnounceDto {
        $stmt = $this->db->prepare('SELECT * FROM ANNOUNCES WHERE TIMESTAMP = (SELECT MAX(TIMESTAMP) FROM ANNOUNCES)');
        $result = $stmt->execute();
        $rows = $result->fetchArray();
        if ($rows === false) {
            return null;
        }
        return new AnnounceDto($rows['content'], $rows['type'], $rows['timestamp'], $rows['id']);
    }

    public function getOldestAnnounce() : ?AnnounceDto {
        $stmt = $this->db->prepare('SELECT * FROM ANNOUNCES WHERE TIMESTAMP = (SELECT MIN(TIMESTAMP) FROM ANNOUNCES)');
        $result = $stmt->execute();
        $rows = $result->fetchArray();
        if ($rows === false) {
            return null;
        }
        return new AnnounceDto($rows['content'], $rows['type'], $rows['timestamp'], $rows['id']);
    }

    public function register(AnnounceDto $dto) : int {
        $stmt = $this->db->prepare('INSERT INTO ANNOUNCES(CONTENT, TYPE, TIMESTAMP) VALUES (:content, :type, :timestamp)');
        $stmt->bindValue(':content', $dto->getContent());
        $stmt->bindValue(':type', $dto->getType());
        $stmt->bindValue(':timestamp', $dto->getTimestamp());
        $stmt->execute();
        return $this->db->lastInsertRowID();
    }

    private function createTable() {
        $this->db->exec('CREATE TABLE IF NOT EXISTS ANNOUNCES (ID INTEGER PRIMARY KEY AUTOINCREMENT, CONTENT TEXT NOT NULL , TYPE INTEGER NOT NULL , TIMESTAMP INTEGER NOT NULL)');
    }
}
