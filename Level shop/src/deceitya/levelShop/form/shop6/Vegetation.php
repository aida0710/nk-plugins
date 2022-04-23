<?php

namespace deceitya\levelShop\form\shop6;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SecondBackFormButton;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;

class Vegetation extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaBlocks::DANDELION()->asItem(),
            VanillaBlocks::POPPY()->asItem(),
            VanillaBlocks::BLUE_ORCHID()->asItem(),
            VanillaBlocks::ALLIUM()->asItem(),
            VanillaBlocks::AZURE_BLUET()->asItem(),
            VanillaBlocks::RED_TULIP()->asItem(),
            VanillaBlocks::ORANGE_TULIP()->asItem(),
            VanillaBlocks::WHITE_TULIP()->asItem(),
            VanillaBlocks::PINK_TULIP()->asItem(),
            VanillaBlocks::OXEYE_DAISY()->asItem(),
            VanillaBlocks::CORNFLOWER()->asItem(),
            VanillaBlocks::LILY_OF_THE_VALLEY()->asItem(),
            VanillaBlocks::LILAC()->asItem(),
            VanillaBlocks::ROSE_BUSH()->asItem(),
            VanillaBlocks::PEONY()->asItem(),
            VanillaBlocks::FERN()->asItem(),
            VanillaBlocks::LARGE_FERN()->asItem(),
            VanillaBlocks::TALL_GRASS()->asItem(),
            VanillaBlocks::DOUBLE_TALLGRASS()->asItem(),
            VanillaBlocks::DEAD_BUSH()->asItem(),
            VanillaBlocks::DEAD_BUSH()->asItem(),
            VanillaBlocks::DEAD_BUSH()->asItem(),
            VanillaBlocks::LILY_PAD()->asItem(),
            VanillaBlocks::VINES()->asItem(),
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

