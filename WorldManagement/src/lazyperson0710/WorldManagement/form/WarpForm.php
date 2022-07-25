<?php

namespace lazyperson0710\WorldManagement\form;

use bbo51dog\bboform\element\ButtonImage;
use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use lazyperson0710\WorldManagement\form\element\CommandDispatchButton;
use lazyperson0710\WorldManagement\form\element\SendFormButton;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\World;

class WarpForm extends SimpleForm {

    public function __construct(Player $player, ?string $error = null) {
        $worldM = Server::getInstance()->getWorldManager();
        $facilities = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                $this->addButton($player, $worldM->getWorldByName("lobby"), "ロビー", "warp lobby", true),
                $this->addButton($player, $worldM->getWorldByName("tos"), "ルールワールド", "warp tos", true),
                $this->addButton($player, $worldM->getWorldByName("athletic"), "アスレチックワールド", "warp athletic", true),
                $this->addButton($player, $worldM->getWorldByName("pvp"), "PVPワールド", "pvp", true),
                new SendFormButton($this, "戻る", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/undoArrow.png")),
            );
        $cities = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                $this->addButton($player, $worldM->getWorldByName("生物市-c"), "生物市", "warp 生物市", true),
                $this->addButton($player, $worldM->getWorldByName("船橋市-c"), "船橋市", "warp 船橋市", true),
                $this->addButton($player, $worldM->getWorldByName("lobby"), "なんとか市", "warp なんとか市", false),
                new SendFormButton($this, "戻る", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/undoArrow.png")),
            );
        $farms = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                $this->addButton($player, $worldM->getWorldByName("浜松市-f"), "浜松市", "warp 浜松市", true),
                $this->addButton($player, $worldM->getWorldByName("八街市-f"), "八街市", "warp 八街市", false),
                $this->addButton($player, $worldM->getWorldByName("lobby"), "なんとか市", "warp なんとか市", false),
                new SendFormButton($this, "戻る", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/undoArrow.png")),
            );
        $normal_resources = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                $this->addButton($player, $worldM->getWorldByName("nature-1"), "オーバーワールド - 1", "warp nature-1", true),
                $this->addButton($player, $worldM->getWorldByName("nature-2"), "オーバーワールド - 2", "warp nature-2", true),
                $this->addButton($player, $worldM->getWorldByName("nature-3"), "オーバーワールド - 3", "warp nature-3", true),
                $this->addButton($player, $worldM->getWorldByName("nature-4"), "オーバーワールド - 4", "warp nature-4", true),
                $this->addButton($player, $worldM->getWorldByName("nature-java"), "オーバーワールド - java", "warp java", true),
                $this->addButton($player, $worldM->getWorldByName("MiningWorld"), "マイニングワールド", "warp mining", true),
                $this->addButton($player, $worldM->getWorldByName("nature-5"), "オーバーワールド - 5", "warp nature-5", true),
                $this->addButton($player, $worldM->getWorldByName("nature-6"), "オーバーワールド - 6", "warp nature-6", true),
                $this->addButton($player, $worldM->getWorldByName("nature-7"), "オーバーワールド - 7", "warp nature-7", true),
                $this->addButton($player, $worldM->getWorldByName("nature-8"), "オーバーワールド - 8", "warp nature-8", true),
                new SendFormButton($this, "戻る", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/undoArrow.png")),
            );
        $resources = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                $this->addButton($player, $worldM->getWorldByName("resource"), "人工資源", "warp resource", true),
                $this->addButton($player, $worldM->getWorldByName("nether-1"), "ネザーディメンション - 1", "warp nether-1", true),
                $this->addButton($player, $worldM->getWorldByName("nether-2"), "ネザーディメンション - 2", "warp nether-2", true),
                $this->addButton($player, $worldM->getWorldByName("nether-3"), "ネザーディメンション - 3", "warp nether-3", true),
                $this->addButton($player, $worldM->getWorldByName("end-1"), "エンドディメンション - 1", "warp end-1", true),
                $this->addButton($player, $worldM->getWorldByName("end-2"), "エンドディメンション - 2", "warp end-2", true),
                $this->addButton($player, $worldM->getWorldByName("end-3"), "エンドディメンション - 3", "warp end-3", true),
                new SendFormButton($this, "戻る", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/undoArrow.png")),
            );
        $events = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                $this->addButton($player, $worldM->getWorldByName("nether-1"), "旧ロビーに行く", "warp event1", true),
                $this->addButton($player, $worldM->getWorldByName("nether-2"), "スキンコンテストイベント", "warp event2", true),
                $this->addButton($player, $worldM->getWorldByName("lobby"), "イベント - 3", "warp event3", false),
                $this->addButton($player, $worldM->getWorldByName("lobby"), "イベント - 4", "warp event4", false),
                $this->addButton($player, $worldM->getWorldByName("lobby"), "イベント - 5", "warp event5", false),
                $this->addButton($player, $worldM->getWorldByName("lobby"), "イベント - 6", "warp event6", false),
                $this->addButton($player, $worldM->getWorldByName("lobby"), "イベント - 7", "warp event7", false),
                $this->addButton($player, $worldM->getWorldByName("lobby"), "イベント - 8", "warp event8", false),
                new SendFormButton($this, "戻る", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/undoArrow.png")),
            );
        $cityAndFarm = (new SimpleForm())
            ->setTitle("World Select")
            ->addElements(
                new SendFormButton($cities, "生活ワールド", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/icon_recipe_item.png")),
                new SendFormButton($farms, "農業ワールド", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/icon_new.png")),
                new SendFormButton($this, "戻る", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/undoArrow.png")),
            );
        $this
            ->setTitle("World Select")
            ->setText("このFormは/wpでも使用可能です{$error}")
            ->addElements(
                new SendFormButton($facilities, "公共施設", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/icon_best3.png")),
                new SendFormButton($cityAndFarm, "農業 & 生活ワールド", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/inventory_icon.png")),
                new SendFormButton($normal_resources, "オーバーワールド", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/icon_recipe_nature.png")),
                new SendFormButton($resources, "特殊資源", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/realmsIcon.png")),
                new SendFormButton($events, "イベント", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/purtle.png")),
                $this->addButton($player, $worldM->getWorldByName("resource"), "オリジナルレシピ", "warp recipe", true, new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/smithing_icon.png")),
            );
    }

    private function addButton(Player $player, World $world, string $text, string $command, bool $permission, ?ButtonImage $buttonImage = null): CommandDispatchButton {
        $worldRequestLevel = WorldManagementAPI::getInstance()->getMiningLevelLimit($world->getFolderName());
        if ($permission === true) {
            if (is_null($buttonImage)) $buttonImage = new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/worldsIcon.png");
            if (MiningLevelAPI::getInstance()->getLevel($player) >= $worldRequestLevel) {
                $text .= "\n§a{$worldRequestLevel}以上の為開放済み";
            } else {
                $permission = false;
                $text .= "\n§c{$worldRequestLevel}レベル以上で開放されます";
            }
        } else {
            if (is_null($buttonImage)) $buttonImage = new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/world_glyph_desaturated.png");
            $text .= "\n§cこのワールドは現在解放されていません";
        }
        return new CommandDispatchButton($text, $command, $permission, $buttonImage);
    }
}