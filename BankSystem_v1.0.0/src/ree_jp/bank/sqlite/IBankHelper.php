<?php

namespace ree_jp\bank\sqlite;

interface IBankHelper {

    /**
     * @return BankHelper
     */
    static function getInstance(): BankHelper;

    /**
     * @param string $bank
     * @return bool
     */
    public function isExists(string $bank): bool;

    /**
     * @param string $bank
     * @param String $leader
     */
    public function create(string $bank, string $leader): void;

    /**
     * @param string $bank
     */
    public function remove(string $bank): void;

    /**
     * @param string $bank
     * @return string
     */
    public function getLeader(string $bank): string;

    /**
     * @param string $bank
     * @param string $name
     * @return bool
     */
    public function isShare(string $bank, string $name): bool;

    /**
     * @param string $bank
     * @param string $target
     * @param string $name
     */
    public function share(string $bank, string $target, string $name): void;

    /**
     * @param string $bank
     * @param string $target
     * @param string $name
     */
    public function removeShare(string $bank, string $target, string $name): void;

    /**
     * @param string $bank
     * @return string[]
     */
    public function getAllShare(string $bank): array;

    /**
     * @param string $bank
     * @return int
     */
    public function getMoney(string $bank): int;

    /**
     * @param string $bank
     * @param string $name
     * @param int $money
     */
    public function addMoney(string $bank, string $name, int $money): void;

    /**
     * @param string $bank
     * @param string $name
     * @param int $money
     */
    public function removeMoney(string $bank, string $name, int $money): void;

    /**
     * @param string $name
     * @return string[]
     */
    public function getAllLeaderBank(string $name): array;

    /**
     * @param string $name
     * @return string[]
     */
    public function getAllBank(string $name): array;

    /**
     * @param string $bank
     * @param string $name
     * @param int $money
     * @param string $target
     * @return bool
     */
    public function transferMoney(string $bank, string $name, int $money, string $target): bool;

    /**
     * @return array[]
     */
    public function getBankDate(): array;
}
