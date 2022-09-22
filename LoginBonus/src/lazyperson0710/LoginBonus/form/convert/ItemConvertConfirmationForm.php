<?php

namespace lazyperson0710\LoginBonus\form\convert;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\LoginBonus\calculation\checkInventoryItem;
use lazyperson0710\LoginBonus\dataBase\LoginBonusItemInfo;
use pocketmine\player\Player;

class ItemConvertConfirmationForm extends CustomForm {

    private LoginBonusItemInfo $item;

    public function __construct(LoginBonusItemInfo $itemInfo) {
        $this->item = $itemInfo;
        $this
            ->setTitle("Login Bonus")
            ->addElements(
                new Label("以下のアイテムと交換しますか？"),
                new Label("アイテム名 : " . $itemInfo->getCustomName() . " x" . $itemInfo->getQuantity()),
                new Label("交換コスト : " . $itemInfo->getCost() . "個"),
            );
    }

    public function handleSubmit(Player $player): void {
        $item = $this->item->getItem();
        $item->setCount($this->item->getQuantity());
        $item->setCustomName($this->item->getCustomName());
        $item->setLore($this->item->getLore());
        //enchants
        //nbtTags
        if (!$player->getInventory()->canAddItem($item)){
            $player->sendMessage("インベントリに空きがないため処理が中断されました");
            return;
        }
        if (checkInventoryItem::init($player, $this->item->getCost())) {
            $player->getInventory()->addItem($item);
            $player->sendMessage("§aログインボーナスを" . $this->item->getCost() . "個消費してチケット" . $this->item->getQuantity() . "枚に交換しました");
        } else {
            $player->sendMessage("インベントリ内にあるログインボーナス数が足りません");
        }
    }
}