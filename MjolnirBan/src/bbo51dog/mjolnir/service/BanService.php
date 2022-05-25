<?php

namespace bbo51dog\mjolnir\service;

use bbo51dog\mjolnir\MjolnirPlugin;
use bbo51dog\mjolnir\model\Account;
use bbo51dog\mjolnir\model\Ban;
use bbo51dog\mjolnir\model\BanType;
use bbo51dog\mjolnir\repository\AccountRepository;
use bbo51dog\mjolnir\repository\BanRepository;
use bbo51dog\mjolnir\Setting;
use pocketmine\Server;

class BanService {

    private function __construct() {
    }

    public static function banName(string $name, string $reason) {
        /** @var AccountRepository $accountRepo */
        $accountRepo = MjolnirPlugin::getRepositoryFactory()->getRepository(AccountRepository::class);
        /** @var BanRepository $banRepo */
        $banRepo = MjolnirPlugin::getRepositoryFactory()->getRepository(BanRepository::class);
        $banRepo->register(new Ban($name, BanType::PLAYER_NAME(), $reason));
        self::banAccounts($accountRepo->getAccountsByName($name), $reason);
        self::checkBannedPlayers();
    }

    /**
     * @param Account[] $accounts
     * @return void
     */
    private static function banAccounts(array $accounts, string $reason) {
        foreach ($accounts as $account) {
            self::banAccount($account, $reason);
        }
    }

    private static function banAccount(Account $account, string $reason) {
        /** @var BanRepository $banRepo */
        $banRepo = MjolnirPlugin::getRepositoryFactory()->getRepository(BanRepository::class);
        /** @var AccountRepository $accountRepo */
        $accountRepo = MjolnirPlugin::getRepositoryFactory()->getRepository(AccountRepository::class);
        if (!$banRepo->isBannedName($account->getName())) {
            $banRepo->register(new Ban($account->getName(), BanType::PLAYER_NAME(), $reason));
            self::banAccounts($accountRepo->getAccountsByName($account->getName()), "Related to {$account->getName()}");
        }
        if (!$banRepo->isBannedCid($account->getCid())) {
            $banRepo->register(new Ban($account->getCid(), BanType::CID(), $reason));
            self::banAccounts($accountRepo->getAccountsByCid($account->getCid()), "Related to {$account->getName()}");
        }
        if (!$banRepo->isBannedXuid($account->getXuid())) {
            $banRepo->register(new Ban($account->getXuid(), BanType::XUID(), $reason));
            self::banAccounts($accountRepo->getAccountsByXuid($account->getXuid()), "Related to {$account->getName()}");
        }
    }

    private static function checkBannedPlayers(): void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if (self::isBanned(Account::createFromPlayer($player))) {
                $player->kick(Setting::getInstance()->getKickMessage());
            }
        }
    }

    public static function isBanned(Account $account): bool {
        /** @var BanRepository $banRepo */
        $banRepo = MjolnirPlugin::getRepositoryFactory()->getRepository(BanRepository::class);
        return $banRepo->isBannedName($account->getName()) || $banRepo->isBannedCid($account->getCid()) || $banRepo->isBannedXuid($account->getXuid());
    }

    public static function banCid(int $cid, string $reason) {
        /** @var AccountRepository $accountRepo */
        $accountRepo = MjolnirPlugin::getRepositoryFactory()->getRepository(AccountRepository::class);
        /** @var BanRepository $banRepo */
        $banRepo = MjolnirPlugin::getRepositoryFactory()->getRepository(BanRepository::class);
        $banRepo->register(new Ban($cid, BanType::CID(), $reason));
        self::banAccounts($accountRepo->getAccountsByCid($cid), $reason);
        self::checkBannedPlayers();
    }

    public static function banXuid(int $xuid, string $reason) {
        /** @var AccountRepository $accountRepo */
        $accountRepo = MjolnirPlugin::getRepositoryFactory()->getRepository(AccountRepository::class);
        /** @var BanRepository $banRepo */
        $banRepo = MjolnirPlugin::getRepositoryFactory()->getRepository(BanRepository::class);
        $banRepo->register(new Ban($xuid, BanType::XUID(), $reason));
        self::banAccounts($accountRepo->getAccountsByXuid($xuid), $reason);
        self::checkBannedPlayers();
    }
}