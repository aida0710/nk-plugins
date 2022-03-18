<?php

namespace deceitya\miningTools\diamond;

use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;

class DiamondConfirmForm implements Form {

    private Item $item;
    private int $price;

    public function __construct(Item $item, int $price) {
        $this->item = $item;
        $this->price = $price;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data) {
            if ($player->getInventory()->canAddItem($this->item)) {
                if (EconomyAPI::getInstance()->myMoney($player) >= $this->price) {
                    $player->getInventory()->addItem($this->item);
                    EconomyAPI::getInstance()->reduceMoney($player, $this->price);
                    $player->sendMessage('§bMiningTool §7>> §aDiamondMiningToolsを購入しました。');
                } else {
                    $player->sendMessage('§bMiningTool §7>> §cお金が足りません。');
                }
            } else {
                $player->sendMessage('§bMiningTool §7>> §cインベントリに空きがありません。');
            }
        } else {
            $player->sendMessage('§bMiningTool §7>> §a購入をキャンセルしました。');
        }
    }

    public function jsonSerialize() {
        return [
            'type' => 'modal',
            'title' => 'DiamondMiningTools',
            'content' => "{$this->price}円です。\n本当に購入しますか。",
            'button1' => 'はい',
            'button2' => 'いいえ'
        ];
    }
}
