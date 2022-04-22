<?php

namespace bbo51dog\mjolnir\repository;

use bbo51dog\mjolnir\model\Account;

interface AccountRepository extends Repository {

    /**
     * @param string $name
     * @return Account[]
     */
    public function getAccountsByName(string $name): array;

    //    /**
    //     * @param string $ip
    //     * @return Account[]
    //     */
    //    public function getAccountsByIp(string $ip): array;
    /**
     * @param int $cid
     * @return Account[]
     */
    public function getAccountsByCid(int $cid): array;

    /**
     * @param string $xuid
     * @return Account[]
     */
    public function getAccountsByXuid(string $xuid): array;

    /**
     * @param Account $account
     * @return void
     */
    public function register(Account $account): void;

    /**
     * @param Account $account
     * @return void
     */
    public function registerIfNotExists(Account $account): void;

    /**
     * @param Account $account
     * @return bool
     */
    public function exists(Account $account): bool;
}