<?php

namespace lazyperson710\core\packet;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SendTip {

    public static function Send(Player $player, string $message, string $prefix, bool $error, ?string $sound = null): void {
        if ($error === true) {
            if ($sound === null) {
                $sound = 'note.bass';
            }
            $type = TextFormat::RED;
        } else {
            if ($sound === null) {
                $sound = 'note.harp';
            }
            $type = TextFormat::GREEN;
        }
        SoundPacket::Send($player, $sound);
        $player->sendTip(TextFormat::BLUE . $prefix . TextFormat::GRAY . " >> " . $type . $message);
    }
}