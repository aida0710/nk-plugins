<?php

namespace deceitya\miningtools\tools\diamond;

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
        if (!$data) {
            $player->sendMessage('§bMiningTool §7>> §a購入をキャンセルしました。');
        }
        if (!$player->getInventory()->canAddItem($this->item)) {
            $player->sendMessage('§bMiningTool §7>> §cインベントリに空きがありません。');
        }
        if (EconomyAPI::getInstance()->myMoney($player) < $this->price) {
            $player->sendMessage('§bMiningTool §7>> §cお金が足りません。');
        }
        $player->getInventory()->addItem($this->item);
        EconomyAPI::getInstance()->reduceMoney($player, $this->price);
        $player->sendMessage('§bMiningTool §7>> §aDiamondMiningToolsを購入しました。');
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
