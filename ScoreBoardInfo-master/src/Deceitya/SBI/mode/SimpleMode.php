<?php

namespace Deceitya\SBI\mode;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class SimpleMode implements Mode {

    public function __construct() {
        $manager = Server::getInstance()->getPluginManager();
        $this->eco = $manager->getPlugin('EconomyAPI');
        $this->rem = $manager->getPlugin('EntityRemover');
        $this->stp = $manager->getPlugin('ServerStopper');
    }

    public function getLines(Player $player): ?array {
        return [
            "所持金 - " . $this->eco->myMoney($player),
            "オンライン - " . count(Server::getInstance()->getOnlinePlayers()) . "/" .Server::getInstance()->getMaxPlayers(),
            "応答速度 - {$player->getNetworkSession()->getPing()}ms",
            "",
            "再起動 - " . (int)($this->stp->getCurrent() / 60) . "分" . ($this->stp->getCurrent() % 60) . "秒",
            "エンティティ削除 - " . (int)($this->rem->getCurrent() / 60) . "分" . ($this->rem->getCurrent() % 60) . "秒",
            "",
            TextFormat::YELLOW . "nkserver.net",
        ];
    }

    public function getId(): int {
        return self::SIMPLE;
    }

    public function getName(): string {
        return "最低限";
    }
}