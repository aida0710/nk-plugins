<?php

namespace Deceitya\SBI\mode;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class NormalMode implements Mode {

    public function __construct() {
        $manager = Server::getInstance()->getPluginManager();
        $this->eco = $manager->getPlugin('EconomyAPI');
        $this->rem = $manager->getPlugin('EntityRemover');
        $this->stp = $manager->getPlugin('ServerStopper');
    }

    public function getLines(Player $player): ?array {
        $item = $player->getInventory()->getItemInHand();
        return [
            "所持金 - " . $this->eco->myMoney($player),
            "ワールド - {$player->getPosition()->getWorld()->getFolderName()}",
            "オンライン - " . count(Server::getInstance()->getOnlinePlayers()) . "/" .Server::getInstance()->getMaxPlayers(),
            "  ",
            "アイテムID - {$item->getId()}:{$item->getMeta()}/{$item->getCount()}",
            "応答速度 - {$player->getNetworkSession()->getPing()}ms",
            " ",
            "サーバー再起動 - " . (int)($this->stp->getCurrent() / 60) . "分" . ($this->stp->getCurrent() % 60) . "秒",
            "エンティティ削除 - " . (int)($this->rem->getCurrent() / 60) . "分" . ($this->rem->getCurrent() % 60) . "秒",
            "",
            TextFormat::YELLOW . "nkserver.net",
        ];
    }

    public function getId(): int {
        return self::NORMAL;
    }

    public function getName(): string {
        return "ノーマル";
    }
}