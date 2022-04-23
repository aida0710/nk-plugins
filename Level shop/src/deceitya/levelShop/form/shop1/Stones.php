<?php

namespace deceitya\levelShop\form\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SecondBackFormButton;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;

class Stones extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaBlocks::DIRT()->asItem(),
            VanillaBlocks::STONE()->asItem(),
            VanillaBlocks::COBBLESTONE()->asItem(),
            VanillaBlocks::GRANITE()->asItem(),
            VanillaBlocks::DIORITE()->asItem(),
            VanillaBlocks::ANDESITE()->asItem(),
            VanillaBlocks::SAND()->asItem(),
            VanillaBlocks::SANDSTONE()->asItem(),
            VanillaBlocks::GRAVEL()->asItem(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId() ,$content->getMeta())} / 売却:{$shop->getSell($content->getId() ,$content->getMeta())}", $content->getId(), $content->getMeta()));
        }
        $shopNumber = basename(__DIR__);
        $shopNumber = str_replace("shop", "", $shopNumber);
        $shopNumber = (int)$shopNumber;
        $this->addElements(new SecondBackFormButton("一つ戻る", $shopNumber));
    }
}
