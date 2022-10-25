<?php

namespace lazyperson710\core\packet;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SendNoSoundTip {

    public static function Send(Player $player, string $message, string $prefix, bool $error): void {
        if ($error === true) {
            $type = TextFormat::RED;
        } else {
            $type = TextFormat::GREEN;
        }
        $player->sendTip(TextFormat::BLUE . $prefix . TextFormat::GRAY . " >> " . $type . $message);
    }
}