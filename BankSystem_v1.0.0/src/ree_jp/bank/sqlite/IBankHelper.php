<?php

declare(strict_types = 0);

namespace ree_jp\bank\sqlite;

interface IBankHelper {

    static function getInstance() : BankHelper;

    public function isExists(string $bank) : bool;

    public function create(string $bank, string $leader) : void;

    public function remove(string $bank) : void;

    public function getLeader(string $bank) : string;

    public function isShare(string $bank, string $name) : bool;

    public function share(string $bank, string $target, string $name) : void;

    public function removeShare(string $bank, string $target, string $name) : void;

    /**
     * @return string[]
     */
    public function getAllShare(string $bank) : array;

    public function getMoney(string $bank) : int;

    public function addMoney(string $bank, string $name, int $money) : void;

    public function removeMoney(string $bank, string $name, int $money) : void;

    /**
     * @return string[]
     */
    public function getAllLeaderBank(string $name) : array;

    /**
     * @return string[]
     */
    public function getAllBank(string $name) : array;

    public function transferMoney(string $bank, string $name, int $money, string $target) : bool;

    /**
     * @return array[]
     */
    public function getBankDate() : array;
}
