<?php

namespace deceitya\ecoshop\form\shop7;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\element\SellBuyItemFormButton;
use deceitya\ecoshop\form\SellBuyForm;
use ipad54\netherblocks\blocks\Target;
use pocketmine\block\VanillaBlocks;
use pocketmine\form\Form;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class レッドストーン系 extends SimpleForm {

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
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            if (is_int($content)){
                $item = match ($content) {
                    -239 => "target",
                    //356 => "RedstoneRepeater",
                    default => "Undefined Error",
                };
                $this->addElements(new SellBuyItemFormButton("{$item}\n購入:{$shop->getBuy($content)} / 売却:{$shop->getSell($content)}", $content));
                continue;
            }
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId())} / 売却:{$shop->getSell($content->getId())}", $content->getId()));
        }
    }

    /*// [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [151, 0, 25000, 0],##Daylight Sensor
        [410, 0, 25000, 0],##Hopper
        [46, 0, 25000, 0],##TNT
        [-239, 0, 25000, 0],##Target
        [131, 0, 25000, 0],##Tripwire Hook
        [146, 0, 2500, 0],##trap chest
        [76, 0, 2500, 0],##Redstone torch
        [356, 0, 2500, 0],##Redstone Repeater
        [404, 0, 2500, 0],##Redstone Comparator
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop7Form());
            return;
        }
        $player->sendForm(new SellBuyForm(self::CONTENTS[$data]));
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください\n緑の文字のアイテムはただの置物です。機能はないのでご注意ください(白文字は機能するブロックです)",
            'buttons' => [
                ['text' => "日照センサー\n§7購入:2500 / 売却:0"],
                ['text' => "ホッパー\n§7購入:2500 / 売却:0"],
                ['text' => "§aTNT\n§7購入:2500 / 売却:0"],
                ['text' => "§aターゲット\n§7購入:25000 / 売却:0"],
                ['text' => "§aトリップワイヤーフック\n§7購入:25000 / 売却:0"],
                ['text' => "トリップチェスト\n§7購入:2500 / 売却:0"],
                ['text' => "レッドストーントーチ\n§7購入:2500 / 売却:0"],
                ['text' => "レッドストーンリピーター\n§7購入:2500 / 売却:0"],
                ['text' => "レッドストーンコンパレーター\n§7購入:2500 / 売却:0"],
            ]
        ];
    }*/
}
