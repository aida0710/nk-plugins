<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;
use ree_jp\stackstorage\api\StackStorageAPI;

class StackStorageAllForm extends CustomForm {

    private Toggle $Armor;
    private Toggle $Hotbar;

    public function __construct() {
        $this->Armor = new Toggle("Armorインベントリの移動(default/off)", false);
        $this->Hotbar = new Toggle("ホットバーアイテムの移動(default/off)", false);
        $this
            ->setTitle("Stack All")
            ->addElements(
                new label("インベントリ内のアイテムをストレージにストレージに移動します"),
                $this->Armor,
                $this->Hotbar,
            );
    }

    public function handleSubmit(Player $player): void {
        $inventory = $player->getInventory();
        $xuid = $player->getXuid();
        $count = 0;
        $armor_count = 0;
        $hotbarSlotSize = $inventory->getHotbarSize() - 1;
        for ($i = 0, $size = $inventory->getSize(); $i < $size; ++$i) {
            if (($this->Hotbar->getValue() === false) && $i >= 0 && $i <= $hotbarSlotSize) {
                continue;
            }
            $item = clone $inventory->getItem($i);
            StackStorageAPI::$instance->add($xuid, $item);
            $inventory->clear($i);
            $count += $item->getCount();
        }
        $inventory->getContents();
        if ($this->Armor->getValue() === true) {
            $armorInventory = $player->getArmorInventory();
            for ($i = 0, $size = $armorInventory->getSize(); $i < $size; ++$i) {
                $item = clone $armorInventory->getItem($i);
                StackStorageAPI::$instance->add($xuid, $item);
                $armorInventory->clear($i);
                $armor_count += $item->getCount();
            }
            $armorInventory->getContents();
        }
        if (($count + $armor_count) == 0) {
            $player->sendMessage("§bStorage §7>> §cインベントリからは何もストレージに移動されませんでした");
            return;
        }
        if (($armor_count) == 0) {
            $player->sendMessage("§bStorage §7>> §aインベントリから{$count}個のストレージに移動されました");
            return;
        }
        if (($count) == 0) {
            $player->sendMessage("§bStorage §7>> §aArmorインベントリから{$armor_count}個のアイテムがストレージに移動されました");
            return;
        }
        $player->sendMessage("§bStorage §7>> §aインベントリから{$count}個、Armorインベントリからは{$armor_count}個のアイテムがストレージに移動されました");
    }
}