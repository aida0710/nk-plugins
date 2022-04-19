<?php

namespace deceitya\ecoshop\form\shop3;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;

class その他ブロック extends SimpleForm {

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
            $this->addElements(new SellBuyItemFormButton("{$item}\n購入:{$shop->getBuy($content->getId())} / 売却:{$shop->getSell($content->getId())}", $content->getId()));
        }
    }

    /*// [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [174, 0, 50, 0],##Packed Ice
        [49, 0, 50, 5],##Obsidian
        [208, 0, 50, 0],##End Rod
        [145, 0, 150, 0],##Anvil
        [145, 4, 150, 0],##Anvil
        [145, 8, 150, 0],##Anvil
        [205, 0, 3000, 0],##Shulker Box
        [165, 0, 50, 0],##Slime Block
        [47, 0, 50, 0],##Bookshelf
        [30, 0, 50, 0],##Cobweb
        [-196, 0, 250, 0],##Blast Furnace
        [-198, 0, 250, 0],##Smoker
        [-194, 0, 2500, 0],##Lectern
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop3Form());
            return;
        }
        $player->sendForm(new SellBuyForm(self::CONTENTS[$data]));
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => "氷塊\n§7購入:50 / 売却:0"],
                ['text' => "黒曜石\n§7購入:50 / 売却:5"],
                ['text' => "エンドロット\n§7購入:50 / 売却:0"],
                ['text' => "金床\n§7購入:150 / 売却:0"],
                ['text' => "少し壊れた金床\n§7購入:150 / 売却:0"],
                ['text' => "かなり壊れた金床\n§7購入:150 / 売却:0"],
                ['text' => "シェルカーブロック\n§7購入:3000 / 売却:0"],
                ['text' => "スライムブロック\n§7購入:50 / 売却:0"],
                ['text' => "本棚\n§7購入:50 / 売却:0"],
                ['text' => "クモの巣\n§7購入:50 / 売却:0"],
                ['text' => "溶鉱炉\n§7購入:250 / 売却:0"],
                ['text' => "燻製機\n§7購入:250 / 売却:0"],
                ['text' => "書見台\n§7購入:2500 / 売却:0"],
            ]
        ];
    }*/
}
