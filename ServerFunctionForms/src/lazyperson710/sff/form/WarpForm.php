<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\ButtonImage;
use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson710\sff\form\element\CommandDispatchButton;
use lazyperson710\sff\form\element\SendFormButton;
use pocketmine\player\Player;

class WarpForm extends SimpleForm {

    public function __construct(Player $player) {
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 5) {
            $level5 = "§a5レベルの為、開放済み";
        } else {
            $level5 = "§c5レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 10) {
            $level10 = "§a10レベルの為、開放済み";
        } else {
            $level10 = "§c10レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 15) {
            $level15 = "§a15レベルの為、開放済み";
        } else {
            $level15 = "§c15レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 20) {
            $level20 = "§a20レベルの為、開放済み";
        } else {
            $level20 = "§c20レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 30) {
            $level30 = "§a30レベルの為、開放済み";
        } else {
            $level30 = "§c30レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 200) {
            $level200 = "§a200レベルの為、開放済み";
        } else {
            $level200 = "§c200レベル以上で開放されます";
        }
        $backButton = new SendFormButton($this, "戻る", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/undoArrow.png"));
        $facilities = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                new CommandDispatchButton("ロビー", "warp lobby", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("ルールワールド", "warp tos", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("アスレチックワールド\n{$level10}", "warp athletic", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("PVPワールド\n{$level30}", "pvp", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                $backButton,
            );
        $cities = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                new CommandDispatchButton("生物市", "warp 生物市", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("船橋市", "warp 船橋市", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("なんとか市", "warp なんとか市", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/world_glyph_desaturated.png")),
                $backButton,
            );
        $farms = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                new CommandDispatchButton("浜松市\n{$level20}", "warp 浜松市", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("なんとか市", "warp なんとか市", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/world_glyph_desaturated.png")),
                new CommandDispatchButton("なんとか市", "warp なんとか市", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/world_glyph_desaturated.png")),
                $backButton,
            );
        $normal_resources = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                new CommandDispatchButton("オーバーワールド - 1", "warp nature-1", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("オーバーワールド - 2", "warp nature-2", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("オーバーワールド - 3", "warp nature-3", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("オーバーワールド - 4\n{$level15}", "warp nature-4", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("オーバーワールド - java\n{$level15}", "warp java", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("マイニングワールド\n{$level30}", "warp mining", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                $backButton,
            );
        $resources = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                new CommandDispatchButton("人工資源\n{$level30}", "warp resource", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("ネザーディメンション - 1\n{$level5}", "warp nether-1", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("ネザーディメンション - 2\n{$level20}", "warp nether-2", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("ネザーディメンション - 3\n{$level20}", "warp nether-3", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("エンドディメンション - 1\n{$level200}", "warp end-1", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("エンドディメンション - 2\n{$level200}", "warp end-2", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("エンドディメンション - 3\n{$level200}", "warp end-3", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                $backButton,
            );
        $events = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                new CommandDispatchButton("旧ロビーに行く", "warp event-1", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("スキンコンテストイベント", "warp event2", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png")),
                new CommandDispatchButton("イベント - 3", "warp event3", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/world_glyph_desaturated.png")),
                new CommandDispatchButton("イベント - 4", "warp event4", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/world_glyph_desaturated.png")),
                new CommandDispatchButton("イベント - 5", "warp event5", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/world_glyph_desaturated.png")),
                new CommandDispatchButton("イベント - 6", "warp event6", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/world_glyph_desaturated.png")),
                new CommandDispatchButton("イベント - 7", "warp event7", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/world_glyph_desaturated.png")),
                new CommandDispatchButton("イベント - 8", "warp event8", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/world_glyph_desaturated.png")),
                $backButton,
            );
        $cityAndFarm = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                new SendFormButton($cities, "生活ワールド", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/icon_recipe_item.png")),
                new SendFormButton($farms, "農業ワールド", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/icon_new.png")),
                $backButton,
            );
        $this
            ->setTitle("World Select")
            ->setText("このFormは/warpuiで使用可能です")
            ->addElements(
                new SendFormButton($facilities, "公共施設", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/icon_best3.png")),
                new SendFormButton($cityAndFarm, "農業 & 生活ワールド", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/inventory_icon.png")),
                new SendFormButton($normal_resources, "オーバーワールド", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/icon_recipe_nature.png")),
                new SendFormButton($resources, "特殊資源", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/realmsIcon.png")),
                new SendFormButton($events, "イベント", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/purtle.png")),
                new CommandDispatchButton("オリジナルレシピ", "warp recipe", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/smithing_icon.png")),
            );
    }
}