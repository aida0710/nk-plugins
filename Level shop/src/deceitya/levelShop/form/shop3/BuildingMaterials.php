<?php

namespace deceitya\levelShop\form\shop3;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;

class BuildingMaterials extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaBlocks::STONE_BRICKS()->asItem(),
            VanillaBlocks::BRICKS()->asItem(),
            VanillaBlocks::QUARTZ()->asItem(),
            VanillaBlocks::GLASS()->asItem(),
            VanillaBlocks::WOOL()->asItem(),
            VanillaBlocks::PRISMARINE()->asItem(),
            VanillaBlocks::PRISMARINE_BRICKS()->asItem(),
            VanillaBlocks::DARK_PRISMARINE()->asItem(),
            VanillaBlocks::HARDENED_CLAY()->asItem(),
            VanillaBlocks::PURPUR()->asItem(),
            VanillaBlocks::CLAY()->asItem(),
            VanillaBlocks::NETHERRACK()->asItem(),
            VanillaBlocks::END_STONE()->asItem(),
            VanillaBlocks::GLOWSTONE()->asItem(),
            VanillaBlocks::SEA_LANTERN()->asItem(),
            VanillaBlocks::RED_SAND()->asItem(),
            VanillaBlocks::RED_SANDSTONE()->asItem(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId() ,$content->getMeta())} / 売却:{$shop->getSell($content->getId() ,$content->getMeta())}", $content->getId(), $content->getMeta()));
        }
    }
}
