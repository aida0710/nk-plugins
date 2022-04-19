<?php

namespace deceitya\ecoshop\form\shop6;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;

class DecorativeBlock extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            720,
            801,
            -268,
            VanillaBlocks::LANTERN()->asItem(),
            -269,
            VanillaBlocks::SEA_PICKLE()->asItem(),
            758,
            VanillaBlocks::BELL()->asItem(),
            VanillaBlocks::BEACON()->asItem(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            if (is_int($content)) {
                $item = match ($content) {
                    720 => "Campfire",
                    801 => "Soul Campfire",
                    -268 => "Soul Torch",
                    -269 => "Soul Lantern",
                    758 => "Chain",
                    default => "Undefined Error",
                };
                $this->addElements(new SellBuyItemFormButton("{$item}\n購入:{$shop->getBuy($content)} / 売却:{$shop->getSell($content)}", $content));
                continue;
            }
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId())} / 売却:{$shop->getSell($content->getId())}", $content->getId()));
        }
    }
}

