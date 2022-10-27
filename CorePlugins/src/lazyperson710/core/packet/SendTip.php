<?php

namespace lazyperson710\core\packet;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SendTip {

    public static function Send(Player $player, string $message, string $prefix, bool $success, ?string $sound = null): void {
        if ($success === true) {
            if ($sound === null) {
                $sound = 'note.harp';
            }
            $type = TextFormat::GREEN;
        } else {
            if ($sound === null) {
                $sound = 'note.bass';
            }
            $type = TextFormat::RED;
        }
        SoundPacket::Send($player, $sound);
        $player->sendTip(TextFormat::BLUE . $prefix . TextFormat::GRAY . " >> " . $type . $message);
    }
}