<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;
use pocketmine\Server;

class InvClearForm extends CustomForm {

    private Toggle $Armor;
    private Toggle $Hotbar;

    public function __construct() {
        $this->Armor = new Toggle("ArmorInventoryの削除(default/off)", false);
        $this->Hotbar = new Toggle("ホットバーアイテムの削除(default/off)", false);
        $this
            ->setTitle("Land Command")
            ->addElement(
                new label("インベントリ内のアイテムを削除します"),
            );
    }

    public function handleSubmit(Player $player): void {
        for ($i = 0, $size = $inventory->getSize(); $i < $size; ++$i) {
            $item = clone $inventory->getItem($i);
            if ($item->getId() == ItemIds::AIR) continue;
            if ($i >= 0 && $i <= $inventory->getHotbarSize() - 1) continue;

            $count = $item->getCount();
        }
        if ($this->Armor->getValue() === true) {

        }
        if ($this->Hotbar->getValue() === true) {

        }
    }
}