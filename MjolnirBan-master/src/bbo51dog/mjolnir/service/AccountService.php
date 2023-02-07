<?php

declare(strict_types=1);

namespace bbo51dog\mjolnir\service;

use bbo51dog\mjolnir\MjolnirPlugin;
use bbo51dog\mjolnir\model\Account;
use bbo51dog\mjolnir\repository\AccountRepository;
use pocketmine\player\Player;

class AccountService {

	private function __construct() {
	}

	public static function registerPlayerData(Player $player) {
		/** @var AccountRepository $repo */
		$repo = MjolnirPlugin::getRepositoryFactory()->getRepository(AccountRepository::class);
		$repo->registerIfNotExists(Account::createFromPlayer($player));
	}
}
