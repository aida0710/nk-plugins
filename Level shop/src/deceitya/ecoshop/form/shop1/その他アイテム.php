<?php

namespace deceitya\ecoshop\form\shop1;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\element\SellBuyItemFormButton;
use lazyperson710\sff\form\element\SendFormButton;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;

class その他アイテム extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaItems::WRITABLE_BOOK(),
            VanillaBlocks::STONE(),
        ];

        $this
            ->setTitle("Expansion Mining Tools")
            ->setText("§7選択してください")
            ->addElements();
        foreach ($contents as $content) {
            //new SendFormButton(new 石材系(), $content->getName());
            new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId())} / 売却:{$shop->getSell($content->getId())}", $content->getId());
        };
    }
}
