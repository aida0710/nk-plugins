<?php

namespace deceitya\miningtools\tools\netherite;

use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\Server;

class NetheriteConfirmForm implements Form {

    private Item $item;
    private int $price;

    public function __construct(Item $item, int $price) {
        $this->item = $item;
        $this->price = $price;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data) {
            if ($player->getInventory()->canAddItem($this->item)) {
                if (!EconomyAPI::getInstance()->myMoney($player) >= $this->price) {
                    $user = $player->getName();
                    EconomyAPI::getInstance()->reduceMoney($player, $this->price);
                    $player->getInventory()->addItem($this->item);
                    Server::getInstance()->broadcastMessage("§bMiningTools §7>> §e{$user}がNetheriteMiningToolsを購入しました");
                } else {
                    $player->sendMessage('§bMiningTools §7>> §cお金が足りません');
                    return;
                }
            } else {
                $player->sendMessage('§bMiningTools §7>> §cインベントリに空きがありません');
                return;
            }
        } else {
            $player->sendMessage('§bMiningTools §7>> §a購入をキャンセルしました');
            return;
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
