<?php

namespace ree_jp\bank\sqlite;

use onebone\economyapi\EconomyAPI;
use ree_jp\bank\BankPlugin;
use SQLite3;

class BankHelper implements IBankHelper {

    /**
     * @var BankHelper
     */
    private static $instance;

    /**
     * @var SQLite3
     */
    private $db;

    public function __construct(string $path) {
        $this->db = new SQLite3($path);
        $this->db->exec("CREATE TABLE IF NOT EXISTS bank(bank TEXT NOT NULL PRIMARY KEY ,leader TEXT NOT NULL ,member TEXT NOT NULL, money INTEGER NOT NULL )");
    }

    /**
     * @inheritDoc
     */
    function create(string $bank, string $leader): void {
        //作成メッセージ
        $text = $leader . "さんによって作成されました";
        if ($this->isExists($bank)) return;
        $leader = strtolower($leader);
        $void = "";
        $stmt = $this->db->prepare("INSERT INTO bank VALUES (:bank, :leader, :member, :money)");
        $stmt->bindParam(":bank", $bank, SQLITE3_TEXT);
        $stmt->bindParam(":leader", $leader, SQLITE3_TEXT);
        $stmt->bindParam(":member", $leader, SQLITE3_TEXT);
        $stmt->bindValue(":money", 0, SQLITE3_NUM);
        $stmt->execute();
        LogHelper::getInstance()->addLog($bank, $text);
    }

    /**
     * @inheritDoc
     */
    function isExists(string $bank): bool {
        $stmt = $this->db->prepare("SELECT * FROM bank WHERE bank = :bank");
        $stmt->bindParam(":bank", $bank, SQLITE3_TEXT);
        if ($stmt->execute()->fetchArray()) {
            return true;
        } else return false;
    }

    /**
     * @inheritDoc
     */
    static function getInstance(): BankHelper {
        if (self::$instance === null) self::$instance = new BankHelper(BankPlugin::getInstance()->getDataFolder() . 'Bank.db');
        return self::$instance;
    }

    /**
     * @inheritDoc
     */
    function remove(string $bank): void {
        if (!$this->isExists($bank)) return;
        $stmt = $this->db->prepare("DELETE FROM bank WHERE bank = :bank");
        $stmt->bindParam(":bank", $bank, SQLITE3_TEXT);
        $stmt->execute();
    }

    /**
     * @inheritDoc
     */
    function share(string $bank, string $target, string $name): void {
        //共有したメッセージ
        $text = $name . "さんが" . $target . "さんを共有しました";
        if (!$this->isExists($bank) or $this->isShare($bank, $target)) return;
        $target = strtolower($target);
        $array = $this->getAllShare($bank);
        $array[] = $target;
        $member = implode(",", $array);
        $stmt = $this->db->prepare("UPDATE bank SET member = :member WHERE bank = :bank");
        $stmt->bindParam(":member", $member, SQLITE3_TEXT);
        $stmt->bindParam(":bank", $bank, SQLITE3_TEXT);
        $stmt->execute();
        LogHelper::getInstance()->addLog($bank, $text);
    }

    /**
     * @inheritDoc
     */
    function isShare(string $bank, string $name): bool {
        if (!$this->isExists($bank)) return false;
        $name = strtolower($name);
        $stmt = $this->db->prepare("SELECT member FROM bank WHERE bank = :bank");
        $stmt->bindParam(":bank", $bank, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray();
        $array = explode(",", current($result));
        return in_array($name, $array);
    }

    public function getAllShare(string $bank): array {
        if (!$this->isExists($bank)) return [];
        $stmt = $this->db->prepare("SELECT member FROM bank WHERE bank = :bank");
        $stmt->bindParam(":bank", $bank);
        return explode(",", current($stmt->execute()->fetchArray()));
    }

    /**
     * @inheritDoc
     */
    function removeShare(string $bank, string $target, string $name): void {
        //共有をはずすメッセージ
        $text = $name . "さんが" . $target . "さんから共有を外しました";
        if (!$this->isExists($bank) or $this->getLeader($bank) === $target or !$this->isShare($bank, $target)) return;
        $target = strtolower($target);
        $array = $this->getAllShare($bank);
        $member = implode(",", array_diff($array, [$target]));
        $stmt = $this->db->prepare("UPDATE bank SET member = :member WHERE bank = :bank");
        $stmt->bindParam(":member", $member, SQLITE3_TEXT);
        $stmt->bindParam(":bank", $bank, SQLITE3_TEXT);
        $stmt->execute();
        LogHelper::getInstance()->addLog($bank, $text);
    }

    /**
     * @inheritDoc
     */
    function getLeader(string $bank): string {
        if (!$this->isExists($bank)) return "";
        $stmt = $this->db->prepare("SELECT leader FROM bank WHERE bank = :bank");
        $stmt->bindParam(":bank", $bank, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray();
        if ($result) {
            return $result[0];
        } else return "";
    }

    /**
     * @inheritDoc
     */
    function removeMoney(string $bank, string $name, int $money): void {
        //引き出しメッセージ
        $text = $name . "さんが" . $money . "引き出しました";
        $money = $this->getMoney($bank) - $money;
        if (!$this->isExists($bank) or $money < 0) return;
        $stmt = $this->db->prepare("UPDATE bank SET money = :money WHERE bank = :bank");
        $stmt->bindValue(":money", $money, SQLITE3_TEXT);
        $stmt->bindParam(":bank", $bank, SQLITE3_TEXT);
        $stmt->execute();
        LogHelper::getInstance()->addLog($bank, $text);
    }

    /**
     * @inheritDoc
     */
    function getMoney(string $bank): int {
        if (!$this->isExists($bank)) return 0;
        $stmt = $this->db->prepare("SELECT money FROM bank WHERE bank = :bank");
        $stmt->bindParam(":bank", $bank);
        return current($stmt->execute()->fetchArray());
    }

    /**
     * @inheritDoc
     */
    function getAllLeaderBank(string $name): array {
        $name = strtolower($name);
        $result = [];
        $stmt = $this->db->prepare("SELECT bank FROM bank WHERE leader = :leader");
        $stmt->bindParam(":leader", $name);
        $exe = $stmt->execute();
        while ($bank = $exe->fetchArray(SQLITE3_NUM)) $result[] = current($bank);
        return $result;
    }

    /**
     * @inheritDoc
     */
    function getAllBank(string $name): array {
        $like = '%' . strtolower($name) . '%';
        $result = [];
        $stmt = $this->db->prepare("SELECT bank FROM bank WHERE member LIKE :name");
        $stmt->bindParam(":name", $like, SQLITE3_TEXT);
        $exe = $stmt->execute();
        while ($bank = $exe->fetchArray(SQLITE3_NUM)) {
            if ($this->isShare(current($bank), $name)) $result[] = current($bank);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    function transferMoney(string $bank, string $name, int $money, string $target): bool {
        //引き出しメッセージ
        $text = $name . "さんが" . $money . "円" . $target . "さんに送金しました";
        $after = $this->getMoney($bank) - $money;
        if (!$this->isExists($bank) or $after < 0) return false;
        $stmt = $this->db->prepare("UPDATE bank SET money = :money WHERE bank = :bank");
        $stmt->bindValue(":money", $after, SQLITE3_TEXT);
        $stmt->bindParam(":bank", $bank, SQLITE3_TEXT);
        $stmt->execute();
        EconomyAPI::getInstance()->addMoney($target, $money);
        LogHelper::getInstance()->addLog($bank, $text);
        return true;
    }

    /**
     * @inheritDoc
     */
    function addMoney(string $bank, string $name, int $money): void {
        //入金メッセージ
        $text = $name . "さんが" . $money . "入金しました";
        $money += $this->getMoney($bank);
        if (!$this->isExists($bank)) return;
        $stmt = $this->db->prepare("UPDATE bank SET money = :money WHERE bank = :bank");
        $stmt->bindValue(":money", $money, SQLITE3_TEXT);
        $stmt->bindParam(":bank", $bank, SQLITE3_TEXT);
        $stmt->execute();
        LogHelper::getInstance()->addLog($bank, $text);
    }

    /**
     * @inheritDoc
     */
    function getBankDate(): array {
        $result = $this->db->query("SELECT * FROM bank ORDER BY money DESC");
        $date = [];
        while ($bank = $result->fetchArray(SQLITE3_ASSOC)) {
            $date[] = $bank;
        }
        return $date;
    }
}
