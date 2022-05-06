<?php

namespace Deceitya\SBI\mode;

use lazyperson0710\ticket\TicketAPI;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class SimpleMode implements Mode {

    private ?Plugin $EconomyAPI;
    private ?Plugin $EntityRemover;
    private ?Plugin $ServerStopper;

    public function __construct() {
        $manager = Server::getInstance()->getPluginManager();
        $this->EconomyAPI = $manager->getPlugin('EconomyAPI');
        $this->EntityRemover = $manager->getPlugin('EntityRemover');
        $this->ServerStopper = $manager->getPlugin('ServerStopper');
    }

    public function getLines(Player $player): ?array {
        return [
            "所持金 - " . $this->EconomyAPI->myMoney($player),
            "チケット - " . TicketAPI::getInstance()->checkData($player) . "枚",
            "オンライン - " . count(Server::getInstance()->getOnlinePlayers()) . "/" . Server::getInstance()->getMaxPlayers(),
            "応答速度 - {$player->getNetworkSession()->getPing()}ms",
            "",
            "サーバー再起動 - " . (int)($this->ServerStopper->getCurrent() / 60) . "分" . ($this->ServerStopper->getCurrent() % 60) . "秒",
            "エンティティ削除 - " . (int)($this->EntityRemover->getCurrent() / 60) . "分" . ($this->EntityRemover->getCurrent() % 60) . "秒",
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