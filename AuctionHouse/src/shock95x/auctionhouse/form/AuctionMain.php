<?php

namespace shock95x\auctionhouse\form;

use bbo51dog\bboform\element\ButtonImage;
use bbo51dog\bboform\form\SimpleForm;
use pocketmine\player\Player;
use shock95x\auctionhouse\form\element\CommandDispatchButton;
use shock95x\auctionhouse\form\element\SendFormButton;

class AuctionMain extends SimpleForm {

    public function __construct(Player $player) {
        $this
            ->setTitle("Land Command")
            ->setText("選択してください\nチェストGUIが開かない場合はお手数おかけしますがリログを推奨しています")
            ->addElements(
                new CommandDispatchButton("AuctionGUIを開く", "ah shop", new ButtonImage(ButtonImage::TYPE_PATH, "textures/items/carrot_golden.png")),
                new SendFormButton(new SellItemAuctionForm($player), "アイテムを出品する", new ButtonImage(ButtonImage::TYPE_PATH, "textures/items/diamond.png")),
                new CommandDispatchButton("出品期限が切れたアイテム\nキャンセルされたアイテムを表示", "ah expired", new ButtonImage(ButtonImage::TYPE_PATH, "textures/items/potato_poisonous.png")),
                new SendFormButton(new ItemCategorySortAuctionForm(), "カテゴリ指定で表示する", new ButtonImage(ButtonImage::TYPE_PATH, "textures/items/hopper.png")),
                new SendFormButton(new PlayerCategorySortAuctionForm(), "プレイヤー指定で表示する", new ButtonImage(ButtonImage::TYPE_PATH, "textures/items/armor_stand.png")),
            );
    }
}