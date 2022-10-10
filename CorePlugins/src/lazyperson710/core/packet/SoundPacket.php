<?php

namespace lazyperson710\core\packet;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class SoundPacket {

    public static function Send(Player $player, string $soundName, ?int $volume = 1, ?int $pitch = 1, ?bool $division = false, ?int $value = 1): void {
        $sound = new PlaySoundPacket();
        $sound->soundName = $soundName;
        $sound->x = $player->getPosition()->getX();
        $sound->y = $player->getPosition()->getY();
        $sound->z = $player->getPosition()->getZ();
        if ($division === true) {
            $sound->volume = $volume / $value;
            $sound->pitch = $pitch / $value;
        } else {
            $sound->volume = $volume;
            $sound->pitch = $pitch;
        }
        $player->getNetworkSession()->sendDataPacket($sound);
    }

}
