<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson710\sff\form\element\CommandDispatchButton;
use pocketmine\player\Player;

class MiningToolsForm extends SimpleForm {

    public function __construct(Player $player) {
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 15) {//2
            $level15 = "§a15レベルの為、開放済み";
        } else {
            $level15 = "§c15レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 30) {//3
            $level30 = "§a30レベルの為、開放済み";
        } else {
            $level30 = "§c30レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= 250) {//4
            $level250 = "§a250レベルの為、開放済み";
        } else {
            $level250 = "§c250レベル以上で開放されます";
        }
        $this
            ->setTitle("Mining Tools")
            ->setText("見たいコンテンツを選択してください")
            ->addElements(
                new CommandDispatchButton("Diamond Tools - 範囲採掘ツール\n{$level15}", "mt1", null),
                new CommandDispatchButton("Netherite Tools - 範囲採掘ツール\n{$level30}", "mt2", null),
                new CommandDispatchButton("アップグレード - Ex範囲採掘ツール\n{$level250}", "mt3", null),
            );
    }
}