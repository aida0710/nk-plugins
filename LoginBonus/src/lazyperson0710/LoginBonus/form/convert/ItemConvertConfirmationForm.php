<?php

namespace lazyperson0710\LoginBonus\form\convert;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\LoginBonus\calculation\CheckInventoryCalculation;
use lazyperson0710\LoginBonus\item\LoginBonusItemInfo;
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
        if ($this->item->getLore() !== null) {
            $item->setLore($this->item->getLore());
        }
        //todo くそ適当、絶対やれ
        if (!empty($this->item->getEnchants())) {
            foreach ($this->item->getEnchants() as $enchantment) {
                $item->addEnchantment($enchantment);
            }
        }
        if (!$player->getInventory()->canAddItem($item)) {
            $player->sendMessage("インベントリに空きがないため処理が中断されました");
            return;
        }
        if (CheckInventoryCalculation::check($player, $this->item->getCost())) {
            $player->getInventory()->addItem($item);
            $player->sendMessage("§aログインボーナスを" . $this->item->getCost() . "個消費してチケット" . $this->item->getQuantity() . "枚に交換しました");
        } else {
            $player->sendMessage("インベントリ内にあるログインボーナス数が足りません");
        }
    }
}