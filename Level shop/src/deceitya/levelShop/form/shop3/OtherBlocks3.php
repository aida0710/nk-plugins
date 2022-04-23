<?php

namespace deceitya\levelShop\form\shop3;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SecondBackFormButton;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;

class OtherBlocks3 extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaBlocks::PACKED_ICE()->asItem(),
            VanillaBlocks::OBSIDIAN()->asItem(),
            VanillaBlocks::END_ROD()->asItem(),
            VanillaBlocks::ANVIL()->asItem(),
            VanillaBlocks::SHULKER_BOX()->asItem(),
            VanillaBlocks::SLIME()->asItem(),
            VanillaBlocks::BOOKSHELF()->asItem(),
            VanillaBlocks::COBWEB()->asItem(),
            VanillaBlocks::BLAST_FURNACE()->asItem(),
            VanillaBlocks::SMOKER()->asItem(),
            VanillaBlocks::LECTERN()->asItem(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            switch ($content) {
                case VanillaBlocks::SMOKER()->asItem():
                    $item = "燻製気";
                    break;
                case VanillaBlocks::BLAST_FURNACE()->asItem():
                    $item = "溶鉱炉";
                    break;
                case VanillaBlocks::LECTERN()->asItem():
                    $item = "書見台";
                    break;
                default:
                    $item = $content->getName();
                    break;
            }
            $this->addElements(new SellBuyItemFormButton("{$item}\n購入:{$shop->getBuy($content->getId())} / 売却:{$shop->getSell($content->getId())}", $content->getId(), $content->getMeta()));
        }
        $shopNumber = basename(__DIR__);
        $shopNumber = str_replace("shop", "", $shopNumber);
        $shopNumber = (int)$shopNumber;
        $this->addElements(new SecondBackFormButton("一つ戻る", $shopNumber));
    }
}
