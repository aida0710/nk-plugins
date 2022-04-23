<?php

namespace deceitya\levelShop\form\shop4;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SecondBackFormButton;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\item\VanillaItems;

class Weapon extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaItems::IRON_SWORD(),
            VanillaItems::DIAMOND_SWORD(),
            VanillaItems::BOW(),
            VanillaItems::ARROW(),
            VanillaItems::SNOWBALL(),
            VanillaItems::EGG(),
            513,
            772,
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            if (is_int($content)) {
                $item = match ($content) {
                    513 => "Shield",
                    772 => "Spyglass",
                    default => "Undefined Error",
                };
                $this->addElements(new SellBuyItemFormButton("{$item}\n購入:{$shop->getBuy($content)} / 売却:{$shop->getSell($content)}", $content, 0));
                continue;
            }
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId() ,$content->getMeta())} / 売却:{$shop->getSell($content->getId() ,$content->getMeta())}", $content->getId(), $content->getMeta()));
        }
        $shopNumber = basename(__DIR__);
        $shopNumber = str_replace("shop", "", $shopNumber);
        $shopNumber = (int)$shopNumber;
        $this->addElements(new SecondBackFormButton("一つ戻る", $shopNumber));
    }
}
