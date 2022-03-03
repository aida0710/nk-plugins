<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson710\sff\form\element\CommandDispatchButton;
use pocketmine\player\Player;
use pocketmine\Server;

class MiningToolsForm extends SimpleForm {

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
        $this
            ->setTitle("Mining Tools")
            ->setText("見たいコンテンツを選択してください")
            ->addElements(
                new CommandDispatchButton("Diamond Tools - 範囲採掘ツール\n{$level25}", "mt1", null),
                new CommandDispatchButton("Netherite Tools - 範囲採掘ツール\n{$level50}", "mt2", null),
            );
        if (Server::getInstance()->isOp($player->getName())) {
            $this->addElement(new CommandDispatchButton("Debug_Tools", "mt4", null));
        }
    }
}