<?php

namespace deceitya\ShopAPI\form\levelShop\shop3;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\database\LevelShopAPI;
use deceitya\ShopAPI\form\element\SecondBackFormButton;
use deceitya\ShopAPI\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;

class Ores extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaBlocks::COAL_ORE()->asItem(),
            VanillaBlocks::LAPIS_LAZULI_ORE()->asItem(),
            VanillaBlocks::REDSTONE_ORE()->asItem(),
            VanillaBlocks::DIAMOND_ORE()->asItem(),
            VanillaBlocks::NETHER_QUARTZ_ORE()->asItem(),
            VanillaBlocks::EMERALD_ORE()->asItem(),
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
