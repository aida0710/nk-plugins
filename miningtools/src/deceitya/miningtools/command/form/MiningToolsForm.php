<?php

namespace deceitya\miningtools\command\form;

use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\miningtools\command\DiamondMiningToolCommand;
use deceitya\miningtools\command\ExpansionMiningToolCommand;
use deceitya\miningtools\command\NetheriteMiningToolCommand;
use deceitya\miningtools\element\CommandDispatchButton;
use pocketmine\player\Player;

class MiningToolsForm extends SimpleForm {

    public function __construct(Player $player) {
        if (MiningLevelAPI::getInstance()->getLevel($player) >= DiamondMiningToolCommand::DiamondMiningToolsLevelLimit) {//2
            $diamondLimit = "§a" . DiamondMiningToolCommand::DiamondMiningToolsLevelLimit . "レベルの為、開放済み";
        } else {
            $diamondLimit = "§c" . DiamondMiningToolCommand::DiamondMiningToolsLevelLimit . "レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= NetheriteMiningToolCommand::NetheriteMiningToolsLevelLimit) {//3
            $netheriteLimit = "§a" . NetheriteMiningToolCommand::NetheriteMiningToolsLevelLimit . "レベルの為、開放済み";
        } else {
            $netheriteLimit = "§c" . NetheriteMiningToolCommand::NetheriteMiningToolsLevelLimit . "レベル以上で開放されます";
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= ExpansionMiningToolCommand::ExpansionMiningToolsLevelLimit) {//4
            $expansionLimit = "§a" . ExpansionMiningToolCommand::ExpansionMiningToolsLevelLimit . "レベルの為、開放済み";
        } else {
            $expansionLimit = "§c" . ExpansionMiningToolCommand::ExpansionMiningToolsLevelLimit . "レベル以上で開放されます";
        }
        $this
            ->setTitle("Mining Tools")
            ->setText("見たいコンテンツを選択してください")
            ->addElements(
                new CommandDispatchButton("Diamond Tools - 範囲採掘ツール\n{$diamondLimit}", "dmt", null),
                new CommandDispatchButton("Netherite Tools - 範囲採掘ツール\n{$netheriteLimit}", "nmt", null),
                new CommandDispatchButton("アップグレード - Ex範囲採掘ツール\n{$expansionLimit}", "emt", null),
            );
    }
}