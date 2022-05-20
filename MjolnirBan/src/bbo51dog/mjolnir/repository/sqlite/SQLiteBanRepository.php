<?php

namespace bbo51dog\mjolnir\repository\sqlite;

use bbo51dog\mjolnir\model\Ban;
use bbo51dog\mjolnir\model\BanType;
use bbo51dog\mjolnir\repository\BanRepository;
use SQLite3;

class SQLiteBanRepository implements BanRepository {

    private SQLite3 $db;

    public function __construct(SQLite3 $db) {
        $this->db = $db;
        $this->prepareTable();
    }

    private function prepareTable(): void {
        $this->db->query("CREATE TABLE IF NOT EXISTS bans(literal TEXT, banType TEXT, reason TEXT)");
    }

    public function close(): void {
    }

    public function register(Ban $ban): void {
        $stmt = $this->db->prepare("INSERT INTO bans values(:literal, :banType, :reason)");
        $stmt->bindValue(":literal", $ban->getLiteral());
        $stmt->bindValue(":banType", $ban->getType());
        $stmt->bindValue(":reason", $ban->getReason());
        $stmt->execute();
    }

    //    public function isBannedIp(string $ip): bool {
    //        return $this->exists($ip, BanType::IP());
    //    }

    public function isBannedName(string $name): bool {
        return $this->exists($name, BanType::PLAYER_NAME());
    }

    private function exists(int|string $literal, BanType $type): bool {
        $stmt = $this->db->prepare("SELECT COUNT (*) FROM bans WHERE literal = :literal AND banType = :banType");
        $stmt->bindValue(":literal", $literal);
        $stmt->bindValue(":banType", $type);
        $rows = $stmt->execute()->fetchArray();
        return $rows[0] > 0;
    }

    public function isBannedCid(int $cid): bool {
        return $this->exists($cid, BanType::CID());
    }

    public function isBannedXuid(int $xuid): bool {
        return $this->exists($xuid, BanType::XUID());
    }
}