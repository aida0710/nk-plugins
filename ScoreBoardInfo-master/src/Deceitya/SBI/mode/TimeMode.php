<?php

namespace Deceitya\SBI\mode;

use lazyperson0710\ticket\TicketAPI;
use pocketmine\player\Player;
use lazyperson710\core\packet\SendForm;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class TimeMode implements Mode {

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
        $item = $player->getInventory()->getItemInHand();
        $date = date("Y年m月d日");
        $time = date("H時i分s秒");
        return [
            "所持金 - " . number_format($this->EconomyAPI->myMoney($player)) . "円",
            "チケット - " . number_format(TicketAPI::getInstance()->checkData($player)) . "枚",
            "ワールド - {$player->getPosition()->getWorld()->getFolderName()}",
            "オンライン - " . count(Server::getInstance()->getOnlinePlayers()) . "/" . Server::getInstance()->getMaxPlayers(),
            "  ",
            "アイテムID - {$item->getId()}:{$item->getMeta()}/{$item->getCount()}",
            "現在日付 - {$date}",
            "現在時刻 - {$time}",
            "応答速度 - {$player->getNetworkSession()->getPing()}ms",
            " ",
            "サーバー再起動 - " . (int)($this->ServerStopper->getCurrent() / 60) . "分" . ($this->ServerStopper->getCurrent() % 60) . "秒",
            "エンティティ削除 - " . (int)($this->EntityRemover->getCurrent() / 60) . "分" . ($this->EntityRemover->getCurrent() % 60) . "秒",
            "",
            TextFormat::YELLOW . "nkserver.net",
        ];
    }

    public function getId(): int {
        return self::TIME;
    }

    public function getName(): string {
        return "ノーマル+時間表記あり";
    }
}