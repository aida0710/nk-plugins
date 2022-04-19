<?php

namespace deceitya\ecoshop\form\shop5;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;

class OtherBlocks extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaBlocks::SOUL_SAND()->asItem(),
            -236,
            -232,
            -233,
            VanillaBlocks::MAGMA()->asItem(),
            -230,
            VanillaBlocks::NETHER_WART_BLOCK()->asItem(),
            -227,
            -289,
            -272,
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            if (is_int($content)) {
                $item = match ($content) {
                    -236 => "Soul Soil",
                    -232 => "Crimson Nylium",
                    -233 => "Warped Nylium",
                    -230 => "Shroomlight",
                    -227 => "Warped Wart Block",
                    -289 => "Crying Obsidian",
                    -272 => "Respawn Anchor",
                    default => "Undefined Error",
                };
                $this->addElements(new SellBuyItemFormButton("{$item}\n購入:{$shop->getBuy($content)} / 売却:{$shop->getSell($content)}", $content));
                continue;
            }
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId())} / 売却:{$shop->getSell($content->getId())}", $content->getId()));
        }
    }
}
