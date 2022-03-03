<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson710\sff\form\element\CommandDispatchButton;
use pocketmine\player\Player;

class ShopForm extends SimpleForm {

    public function __construct(Player $player) {
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 25) {//2
            $level25 = "§a25レベルの為、開放済み";
        } else {
            $level25 = "§c25レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 50) {//3
            $level50 = "§a50レベルの為、開放済み";
        } else {
            $level50 = "§c50レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 80) {//4
            $level80 = "§a80レベルの為、開放済み";
        } else {
            $level80 = "§c80レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 120) {//5
            $level120 = "§a120レベルの為、開放済み";
        } else {
            $level120 = "§c120レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 180) {//6
            $level180 = "§a180レベルの為、開放済み";
        } else {
            $level180 = "§c180レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 250) {//7
            $level250 = "§a250レベルの為、開放済み";
        } else {
            $level250 = "§c250レベル以上で開放されます";
        }
        $this
            ->setTitle("Shop List")
            ->setText("見たいコンテンツを選択してください")
            ->addElements(
                new CommandDispatchButton("Level Shop - 1", "shop1", null),
                new CommandDispatchButton("Level Shop - 2\n{$level25}", "shop2", null),
                new CommandDispatchButton("Level Shop - 3\n{$level50}", "shop3", null),
                new CommandDispatchButton("Level Shop - 4\n{$level80}", "shop4", null),
                new CommandDispatchButton("Level Shop - 5\n{$level120}", "shop5", null),
                new CommandDispatchButton("Level Shop - 6\n{$level180}", "shop6", null),
                new CommandDispatchButton("Level Shop - 7\n{$level250}", "shop7", null),
            );
    }
}