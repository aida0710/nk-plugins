<?php

declare(strict_types = 0);

namespace bbo51dog\announce\repository;

use bbo51dog\announce\repository\dto\UserDto;
use SQLite3;

class SQLiteUserRepository implements UserRepository {

    private SQLite3 $db;

    public function __construct(SQLite3 $db) {
        $this->db = $db;
        $this->createTable();
    }

    private function createTable() {
        $this->db->exec('CREATE TABLE IF NOT EXISTS USERS (NAME TEXT PRIMARY KEY NOT NULL, ANNOUNCEID INTEGER NOT NULL, HASREAD NUMERIC NOT NULL, CONFIRMED NUMERIC NOT NULL)');
    }

    public function close() : void {
    }

    public function exists(string $name) : bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM USERS WHERE NAME = :name');
        $stmt->bindValue(':name', $name);
        $rows = $stmt->execute()->fetchArray();
        return $rows[0] > 0;
    }

    public function getUser(string $name) : UserDto {
        $stmt = $this->db->prepare('SELECT * FROM USERS WHERE NAME = :name');
        $stmt->bindValue(':name', $name);
        $result = $stmt->execute();
        $rows = $result->fetchArray();
        return new UserDto($name, $rows['confirmed'], $rows['announceId'], $rows['hasRead']);
    }

    public function register(UserDto $dto) : void {
        $stmt = $this->db->prepare('INSERT INTO USERS VALUES (:name, :announceId, :hasRead, :confirmed)');
        $stmt->bindValue(':name', $dto->getName());
        $stmt->bindValue(':announceId', $dto->getAnnounceId());
        $stmt->bindValue(':hasRead', $dto->hasRead());
        $stmt->bindValue(':confirmed', $dto->isConfirmed());
        $stmt->execute();
    }

    public function update(UserDto $dto) : void {
        $stmt = $this->db->prepare('UPDATE USERS SET ANNOUNCEID = :announceId, HASREAD = :hasRead, CONFIRMED = :confirmed WHERE NAME = :name');
        $stmt->bindValue(':announceId', $dto->getAnnounceId());
        $stmt->bindValue(':hasRead', $dto->hasRead());
        $stmt->bindValue(':name', $dto->getName());
        $stmt->bindValue(':confirmed', $dto->isConfirmed());
        $stmt->execute();
    }

    public function updateAllUserAnnounce(int $announceId) : void {
        $stmt = $this->db->prepare('UPDATE USERS SET ANNOUNCEID = :announceId, HASREAD = :hasRead');
        $stmt->bindValue(':announceId', $announceId);
        $stmt->bindValue(':hasRead', false);
        $stmt->execute();
    }
}
