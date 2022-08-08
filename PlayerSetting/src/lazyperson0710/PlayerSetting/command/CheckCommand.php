<?php

namespace lazyperson0710\PlayerSetting\command;

use lazyperson0710\PlayerSetting\object\PlayerDataArray;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class CheckCommand extends Command {

    public function __construct() {
        parent::__construct("check", "現状の確認");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $array = PlayerDataArray::getInstance();
        var_dump("Coordinate ->" . $array->getCoordinate($sender));
        var_dump("DestructionSound ->" . $array->getDestructionSound($sender));
        var_dump("DiceMessage ->" . $array->getDiceMessage($sender));
        var_dump("DirectDropItemStorage ->" . $array->getDirectDropItemStorage($sender));
        var_dump("EnduranceWarning ->" . $array->getEnduranceWarning($sender));
        var_dump("JoinItems ->" . $array->getJoinItems($sender));
        var_dump("LevelUpTitle ->" . $array->getLevelUpTitle($sender));
        var_dump("OnlinePlayersEffects ->" . $array->getOnlinePlayersEffects($sender));
        var_dump("PayCommandUse ->" . $array->getPayCommandUse($sender));
        var_dump("PlayerDropItem ->" . $array->getPlayerDropItem($sender));
    }
}