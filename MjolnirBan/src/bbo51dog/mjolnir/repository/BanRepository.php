<?php

namespace bbo51dog\mjolnir\repository;

use bbo51dog\mjolnir\model\Ban;

interface BanRepository extends Repository {

    public function register(Ban $ban): void;

    public function isBannedName(string $name): bool;

    public function isBannedCid(int $cid): bool;

    public function isBannedXuid(int $xuid): bool;
}