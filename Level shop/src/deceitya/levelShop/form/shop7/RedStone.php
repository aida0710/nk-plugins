<?php

namespace deceitya\levelShop\form\shop7;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;

class RedStone extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaBlocks::DAYLIGHT_SENSOR()->asItem(),
            VanillaBlocks::HOPPER()->asItem(),
            VanillaBlocks::TNT()->asItem(),
            -239,
            VanillaBlocks::TRIPWIRE_HOOK()->asItem(),
            VanillaBlocks::TRAPPED_CHEST()->asItem(),
            VanillaBlocks::REDSTONE_TORCH()->asItem(),
            VanillaBlocks::REDSTONE_REPEATER()->asItem(),
            VanillaBlocks::REDSTONE_COMPARATOR()->asItem(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            if (is_int($content)) {
                $item = match ($content) {
                    -239 => "Target",
                    default => "Undefined Error",
                };
                $this->addElements(new SellBuyItemFormButton("{$item}\n購入:{$shop->getBuy($content)} / 売却:{$shop->getSell($content)}", $content, 0));
                continue;
            }
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId() ,$content->getMeta())} / 売却:{$shop->getSell($content->getId() ,$content->getMeta())}", $content->getId(), $content->getMeta()));
        }
    }
}
