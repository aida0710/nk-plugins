<?php

namespace deceitya\miningtools\netherite;

use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\Server;

class NetheriteConfirmForm implements Form {

    private $item;
    private $price;

    public function __construct(Item $item, int $price) {
        $this->item = $item;
        $this->price = $price;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data) {
            if ($player->getInventory()->canAddItem($this->item)) {
                if (EconomyAPI::getInstance()->myMoney($player) >= $this->price) {
                    $player->getInventory()->addItem($this->item);
                    $user = $player->getName();
                    EconomyAPI::getInstance()->reduceMoney($player, $this->price);
                    Server::getInstance()->broadcastMessage("§bMiningTool §7>> §e{$user}がNetheriteMiningToolsを購入しました");
                } else {
                    $player->sendMessage('§bMiningTool §7>> §cお金が足りません');
                }
            } else {
                $player->sendMessage('§bMiningTool §7>> §cインベントリに空きがありません');
            }
        } else {
            $player->sendMessage('§bMiningTool §7>> §a購入をキャンセルしました');
        }
    }

    public function jsonSerialize() {
        return [
            'type' => 'modal',
            'title' => 'NetheriteMiningTools',
            'content' => "{$this->price}円です\n本当に購入しますか",
            'button1' => 'はい',
            'button2' => 'いいえ'
        ];
    }
}
