<?php

namespace deceitya\levelShop\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use deceitya\levelShop\database\LevelShopAPI;
use pocketmine\player\Player;

class InventorySellButton extends Button {

    /**
     * @param string $text
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
    }

    public function handleSubmit(Player $player): void {
        $inventory = $player->getInventory();
        $count = 0;
        $armor_count = 0;
        for ($i = 0, $size = $inventory->getSize(); $i < $size; ++$i) {
            $item = clone $inventory->getItem($i);
            LevelShopAPI::getInstance()->getSell($item->getId(), $item->getMeta());
            $inventory->clear($i);
            $count += $item->getCount();
        }
        $inventory->getContents();
        $player->sendMessage("§bStorage §7>> §a合計" . ($count + $armor_count) . "個のアイテムを仮想ストレージに収納しました");
    }
}