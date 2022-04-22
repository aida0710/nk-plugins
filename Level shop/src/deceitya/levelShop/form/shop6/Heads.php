<?php

namespace deceitya\levelShop\form\shop6;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\item\VanillaItems;

class Heads extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaItems::PLAYER_HEAD(),
            VanillaItems::ZOMBIE_HEAD(),
            VanillaItems::SKELETON_SKULL(),
            VanillaItems::CREEPER_HEAD(),
            VanillaItems::WITHER_SKELETON_SKULL(),
            VanillaItems::DRAGON_HEAD(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId() ,$content->getMeta())} / 売却:{$shop->getSell($content->getId() ,$content->getMeta())}", $content->getId(), $content->getMeta()));
        }
    }
}

