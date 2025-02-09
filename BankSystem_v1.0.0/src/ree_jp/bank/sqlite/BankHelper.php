<?php

declare(strict_types = 0);

namespace ree_jp\bank\sqlite;

use onebone\economyapi\EconomyAPI;
use ree_jp\bank\BankPlugin;
use SQLite3;
use function array_diff;
use function current;
use function explode;
use function implode;
use function in_array;
use function strtolower;
use const SQLITE3_ASSOC;
use const SQLITE3_NUM;
use const SQLITE3_TEXT;

class BankHelper implements IBankHelper {

    /** @var BankHelper */
    private static $instance;

    /** @var SQLite3 */
    private $db;

    public function __construct(string $path) {
        $this->db = new SQLite3($path);
        $this->db->exec('CREATE TABLE IF NOT EXISTS BANK(BANK TEXT NOT NULL PRIMARY KEY ,LEADER TEXT NOT NULL ,MEMBER TEXT NOT NULL, MONEY INTEGER NOT NULL )');
    }

    /**
     * @inheritDoc
     */
    public function create(string $bank, string $leader) : void {
        //作成メッセージ
        $text = $leader . 'さんによって作成されました';
        if ($this->isExists($bank)) return;
        $leader = strtolower($leader);
        $void = '';
        $stmt = $this->db->prepare('INSERT INTO BANK VALUES (:bank, :leader, :member, :money)');
        $stmt->bindParam(':bank', $bank, SQLITE3_TEXT);
        $stmt->bindParam(':leader', $leader, SQLITE3_TEXT);
        $stmt->bindParam(':member', $leader, SQLITE3_TEXT);
        $stmt->bindValue(':money', 0, SQLITE3_NUM);
        $stmt->execute();
        LogHelper::getInstance()->addLog($bank, $text);
    }

    /**
     * @inheritDoc
     */
    public function isExists(string $bank) : bool {
        $stmt = $this->db->prepare('SELECT * FROM BANK WHERE BANK = :bank');
        $stmt->bindParam(':bank', $bank, SQLITE3_TEXT);
        if ($stmt->execute()->fetchArray()) {
            return true;
        } else return false;
    }

    /**
     * @inheritDoc
     */
    static function getInstance() : BankHelper {
        if (self::$instance === null) self::$instance = new BankHelper(BankPlugin::getInstance()->getDataFolder() . 'Bank.db');
        return self::$instance;
    }

    /**
     * @inheritDoc
     */
    public function getAllBank(string $name) : array {
        $like = '%' . strtolower($name) . '%';
        $result = [];
        $stmt = $this->db->prepare('SELECT BANK FROM BANK WHERE MEMBER LIKE :name');
        $stmt->bindParam(':name', $like, SQLITE3_TEXT);
        $exe = $stmt->execute();
        while ($bank = $exe->fetchArray(SQLITE3_NUM)) {
            if ($this->isShare(current($bank), $name)) $result[] = current($bank);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function isShare(string $bank, string $name) : bool {
        if (!$this->isExists($bank)) return false;
        $name = strtolower($name);
        $stmt = $this->db->prepare('SELECT MEMBER FROM BANK WHERE BANK = :bank');
        $stmt->bindParam(':bank', $bank, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray();
        $array = explode(',', current($result));
        return in_array($name, $array, true);
    }

    /**
     * @inheritDoc
     */
    public function getAllLeaderBank(string $name) : array {
        $name = strtolower($name);
        $result = [];
        $stmt = $this->db->prepare('SELECT BANK FROM BANK WHERE LEADER = :leader');
        $stmt->bindParam(':leader', $name);
        $exe = $stmt->execute();
        while ($bank = $exe->fetchArray(SQLITE3_NUM)) $result[] = current($bank);
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getBankDate() : array {
        $result = $this->db->query('SELECT * FROM BANK ORDER BY MONEY DESC');
        $date = [];
        while ($bank = $result->fetchArray(SQLITE3_ASSOC)) {
            $date[] = $bank;
        }
        return $date;
    }

    /**
     * @inheritDoc
     */
    public function remove(string $bank) : void {
        if (!$this->isExists($bank)) return;
        $stmt = $this->db->prepare('DELETE FROM BANK WHERE BANK = :bank');
        $stmt->bindParam(':bank', $bank, SQLITE3_TEXT);
        $stmt->execute();
    }

    /**
     * @inheritDoc
     */
    public function removeMoney(string $bank, string $name, int $money) : void {
        //引き出しメッセージ
        $text = $name . 'さんが' . $money . '引き出しました';
        $money = $this->getMoney($bank) - $money;
        if (!$this->isExists($bank) || $money < 0) return;
        $stmt = $this->db->prepare('UPDATE BANK SET MONEY = :money WHERE BANK = :bank');
        $stmt->bindValue(':money', $money, SQLITE3_TEXT);
        $stmt->bindParam(':bank', $bank, SQLITE3_TEXT);
        $stmt->execute();
        LogHelper::getInstance()->addLog($bank, $text);
    }

    /**
     * @inheritDoc
     */
    public function getMoney(string $bank) : int {
        if (!$this->isExists($bank)) return 0;
        $stmt = $this->db->prepare('SELECT MONEY FROM BANK WHERE BANK = :bank');
        $stmt->bindParam(':bank', $bank);
        return current($stmt->execute()->fetchArray());
    }

    /**
     * @inheritDoc
     */
    public function removeShare(string $bank, string $target, string $name) : void {
        //共有をはずすメッセージ
        $text = $name . 'さんが' . $target . 'さんから共有を外しました';
        if (!$this->isExists($bank) || $this->getLeader($bank) === $target || !$this->isShare($bank, $target)) return;
        $target = strtolower($target);
        $array = $this->getAllShare($bank);
        $member = implode(',', array_diff($array, [$target]));
        $stmt = $this->db->prepare('UPDATE BANK SET MEMBER = :member WHERE BANK = :bank');
        $stmt->bindParam(':member', $member, SQLITE3_TEXT);
        $stmt->bindParam(':bank', $bank, SQLITE3_TEXT);
        $stmt->execute();
        LogHelper::getInstance()->addLog($bank, $text);
    }

    /**
     * @inheritDoc
     */
    public function getLeader(string $bank) : string {
        if (!$this->isExists($bank)) return '';
        $stmt = $this->db->prepare('SELECT LEADER FROM BANK WHERE BANK = :bank');
        $stmt->bindParam(':bank', $bank, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray();
        if ($result) {
            return $result[0];
        } else return '';
    }

    public function getAllShare(string $bank) : array {
        if (!$this->isExists($bank)) return [];
        $stmt = $this->db->prepare('SELECT MEMBER FROM BANK WHERE BANK = :bank');
        $stmt->bindParam(':bank', $bank);
        return explode(',', current($stmt->execute()->fetchArray()));
    }

    /**
     * @inheritDoc
     */
    public function share(string $bank, string $target, string $name) : void {
        //共有したメッセージ
        $text = $name . 'さんが' . $target . 'さんを共有しました';
        if (!$this->isExists($bank) || $this->isShare($bank, $target)) return;
        $target = strtolower($target);
        $array = $this->getAllShare($bank);
        $array[] = $target;
        $member = implode(',', $array);
        $stmt = $this->db->prepare('UPDATE BANK SET MEMBER = :member WHERE BANK = :bank');
        $stmt->bindParam(':member', $member, SQLITE3_TEXT);
        $stmt->bindParam(':bank', $bank, SQLITE3_TEXT);
        $stmt->execute();
        LogHelper::getInstance()->addLog($bank, $text);
    }

    /**
     * @inheritDoc
     */
    public function transferMoney(string $bank, string $name, int $money, string $target) : bool {
        //引き出しメッセージ
        $text = $name . 'さんが' . $money . '円' . $target . 'さんに送金しました';
        $after = $this->getMoney($bank) - $money;
        if (!$this->isExists($bank) || $after < 0) return false;
        $stmt = $this->db->prepare('UPDATE BANK SET MONEY = :money WHERE BANK = :bank');
        $stmt->bindValue(':money', $after, SQLITE3_TEXT);
        $stmt->bindParam(':bank', $bank, SQLITE3_TEXT);
        $stmt->execute();
        EconomyAPI::getInstance()->addMoney($target, $money);
        LogHelper::getInstance()->addLog($bank, $text);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function addMoney(string $bank, string $name, int $money) : void {
        //入金メッセージ
        $text = $name . 'さんが' . $money . '入金しました';
        $money += $this->getMoney($bank);
        if (!$this->isExists($bank)) return;
        $stmt = $this->db->prepare('UPDATE BANK SET MONEY = :money WHERE BANK = :bank');
        $stmt->bindValue(':money', $money, SQLITE3_TEXT);
        $stmt->bindParam(':bank', $bank, SQLITE3_TEXT);
        $stmt->execute();
        LogHelper::getInstance()->addLog($bank, $text);
    }
}
