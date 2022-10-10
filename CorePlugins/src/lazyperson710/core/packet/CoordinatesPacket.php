<?php

namespace lazyperson710\core\packet;

use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\player\Player;

class CoordinatesPacket {

    public static function Send(Player $player, bool $value): void {
        $pk = new GameRulesChangedPacket();
        $pk->gameRules = ["showcoordinates" => new BoolGameRule($value, false)];
        $player->getNetworkSession()->sendDataPacket($pk);
    }
}