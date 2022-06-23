<?php

namespace deceitya\ShopAPI\form\levelShop\shop7;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\database\LevelShopAPI;
use deceitya\ShopAPI\form\element\SecondBackFormButton;
use deceitya\ShopAPI\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;

class OtherBlocks7 extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaBlocks::ITEM_FRAME()->asItem(),
            VanillaBlocks::FLETCHING_TABLE()->asItem(),
            VanillaBlocks::COMPOUND_CREATOR()->asItem(),
            VanillaBlocks::LOOM()->asItem(),
            VanillaBlocks::ELEMENT_CONSTRUCTOR()->asItem(),
            VanillaBlocks::LAB_TABLE()->asItem(),
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
