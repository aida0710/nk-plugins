<?php

namespace lazyperson0710\EffectItems\command;

use lazyperson0710\EffectItems\form\ItemNameChangeForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ItemNameChangeCommand extends Command {

    public function __construct() {
        parent::__construct("nc", "アイテムを消費して道具の名前を変更する");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        SendForm::Send($sender, (new ItemNameChangeForm()));
    }

}