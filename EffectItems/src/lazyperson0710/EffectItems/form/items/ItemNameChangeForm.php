<?php

namespace lazyperson0710\EffectItems\form\items;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\item\Durable;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class ItemNameChangeForm extends CustomForm {

    private Input $itemName;

    public function __construct() {
        $this->itemName = new Input("所持しているアイテムに付与したい名前を入力してください\n名前を変更できるのは道具のみとなります", "なまけものソード");
        $this
            ->setTitle("Item Edit")
            ->addElements(
                $this->itemName,
            );
    }

    public function handleSubmit(Player $player): void {
        if (!$player->getInventory()->getItemInHand() instanceof Durable) {
            $player->sendMessage("§bItemNameChange §7>> §c道具や装備以外のアイテムは名前を変更することができません");
            return;
        }
        $approval = false;
        for ($i = 0, $size = $player->getInventory()->getSize(); $i < $size; ++$i) {
            $item = clone $player->getInventory()->getItem($i);
            if ($item->getId() == ItemIds::AIR) continue;
            if ($item->getNamedTag()->getTag('ItemNameChangeIngot') !== null) {
                $player->getInventory()->removeItem($item->setCount(1));
                $approval = true;
                break;
            }
        }
        if ($approval === false) {
            $player->sendMessage("§bItemNameChange §7>> §cアイテム名変更アイテムを取得することができませんでした");
            return;
        }
        $inHandItem = $player->getInventory()->getItemInHand();
        $inHandItem->setCustomName($this->itemName->getValue());
        $player->getInventory()->setItemInHand($inHandItem);
        $player->sendMessage("§bItemEdit §7>> §aアイテム名を{$this->itemName->getValue()}§r§aに変更しました");
    }
}
