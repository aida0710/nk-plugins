<?php

namespace lazyperson710\core\packet;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SendNoSoundTip {

    public static function Send(Player $player, string $message, string $prefix, bool $success): void {
        if ($success === true) {
            $type = TextFormat::GREEN;
        } else {
            $type = TextFormat::RED;
        }
        $player->sendTip(TextFormat::BLUE . $prefix . TextFormat::GRAY . " >> " . $type . $message);
    }
}