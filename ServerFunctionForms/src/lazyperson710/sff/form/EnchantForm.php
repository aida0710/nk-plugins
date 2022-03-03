<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\form\element\CommandDispatchButton;
use pocketmine\player\Player;
use pocketmine\Server;

class EnchantForm extends SimpleForm {

    public function __construct(Player $player) {
        $this
            ->setTitle("Enchant Form")
            ->addElements(
                new CommandDispatchButton("通常エンチャントを付与", "ven", null),
                new CommandDispatchButton("エンチャントを削減", "enreduce", null),
                new CommandDispatchButton("エンチャントを削除", "endelete", null),
            );
        if (Server::getInstance()->isOp($player->getName())) {
            $this->addElement(new CommandDispatchButton("アイテムの耐久を無限にする", "edit", null));
        }
    }
}