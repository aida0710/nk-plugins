<?php

declare(strict_types = 0);

namespace bbo51dog\mjolnir\repository;

use bbo51dog\mjolnir\model\Account;

interface AccountRepository extends Repository {

    /**
     * @return Account[]
     */
    public function getAccountsByName(string $name) : array;

    /**
     * @return Account[]
     */
    public function getAccountsByIp(string $ip) : array;

    /**
     * @return Account[]
     */
    public function getAccountsByCid(int $cid) : array;

    /**
     * @return Account[]
     */
    public function getAccountsByXuid(string $xuid) : array;

    public function register(Account $account) : void;

    public function registerIfNotExists(Account $account) : void;

    public function exists(Account $account) : bool;
}
