<?php

declare(strict_types = 0);

namespace Deceitya\SBI\mode;

use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\Main;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use function count;
use function number_format;

class NormalMode implements Mode {

    private ?Plugin $EconomyAPI;
    private ?Plugin $ServerStopper;

    public function __construct() {
        $manager = Server::getInstance()->getPluginManager();
        $this->EconomyAPI = $manager->getPlugin('EconomyAPI');
        $this->ServerStopper = $manager->getPlugin('ServerStopper');
    }

    public function getLines(Player $player) : ?array {
        $item = $player->getInventory()->getItemInHand();
        return [
            '所持金 - ' . number_format($this->EconomyAPI->myMoney($player)) . '円',
            'チケット - ' . number_format(TicketAPI::getInstance()->checkData($player)) . '枚',
            "ワールド - {$player->getPosition()->getWorld()->getFolderName()}",
            'オンライン - ' . count(Server::getInstance()->getOnlinePlayers()) . '/' . Server::getInstance()->getMaxPlayers(),
            '  ',
            "アイテムID - {$item->getId()}:{$item->getMeta()}/{$item->getCount()}",
            "応答速度 - {$player->getNetworkSession()->getPing()}ms",
            ' ',
            'サーバー再起動 - ' . (int) ($this->ServerStopper->getCurrent() / 60) . '分' . ($this->ServerStopper->getCurrent() % 60) . '秒',
            '経験値オーブ削除 - ' . (int) (Main::getInstance()->entityRemoveTimeLeft / 60) . '分' . (Main::getInstance()->entityRemoveTimeLeft % 60) . '秒',
            '',
            TextFormat::YELLOW . 'nkserver.net',
        ];
    }

    public function getId() : int {
        return self::NORMAL;
    }

    public function getName() : string {
        return 'ノーマル';
    }
}
