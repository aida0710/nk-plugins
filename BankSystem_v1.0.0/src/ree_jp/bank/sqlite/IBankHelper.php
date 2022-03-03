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
    function isExists(string $bank): bool;

    /**
     * @param string $bank
     * @param String $leader
     */
    function create(string $bank, string $leader): void;

    /**
     * @param string $bank
     */
    function remove(string $bank): void;

    /**
     * @param string $bank
     * @return string
     */
    function getLeader(string $bank): string;

    /**
     * @param string $bank
     * @param string $name
     * @return bool
     */
    function isShare(string $bank, string $name): bool;

    /**
     * @param string $bank
     * @param string $target
     * @param string $name
     */
    function share(string $bank, string $target, string $name): void;

    /**
     * @param string $bank
     * @param string $target
     * @param string $name
     */
    function removeShare(string $bank, string $target, string $name): void;

    /**
     * @param string $bank
     * @return string[]
     */
    function getAllShare(string $bank): array;

    /**
     * @param string $bank
     * @return int
     */
    function getMoney(string $bank): int;

    /**
     * @param string $bank
     * @param string $name
     * @param int $money
     */
    function addMoney(string $bank, string $name, int $money): void;

    /**
     * @param string $bank
     * @param string $name
     * @param int $money
     */
    function removeMoney(string $bank, string $name, int $money): void;

    /**
     * @param string $name
     * @return string[]
     */
    function getAllLeaderBank(string $name): array;

    /**
     * @param string $name
     * @return string[]
     */
    function getAllBank(string $name): array;

    /**
     * @param string $bank
     * @param string $name
     * @param int $money
     * @param string $target
     * @return bool
     */
    function transferMoney(string $bank, string $name, int $money, string $target): bool;

    /**
     * @return array[]
     */
    function getBankDate(): array;
}
