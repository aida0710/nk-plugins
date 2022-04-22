<?php

namespace deceitya\levelShop\form\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;

class Logs extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaBlocks::OAK_LOG()->asItem(),
            VanillaBlocks::SPRUCE_LOG()->asItem(),
            VanillaBlocks::BIRCH_LOG()->asItem(),
            VanillaBlocks::JUNGLE_LOG()->asItem(),
            VanillaBlocks::ACACIA_LOG()->asItem(),
            VanillaBlocks::DARK_OAK_LOG()->asItem(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId() ,$content->getMeta())} / 売却:{$shop->getSell($content->getId() ,$content->getMeta())}", $content->getId(), $content->getMeta()));
        }
    }
}
