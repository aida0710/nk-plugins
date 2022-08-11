<?php

namespace lazyperson0710\EffectItems\packet;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class SoundPacket {

    public static function init(Player $player, string $soundName, ?int $volume = 1, ?int $pitch = 1): void {
        $sound = new PlaySoundPacket();
        $sound->soundName = $soundName;
        $sound->x = $player->getPosition()->getX();
        $sound->y = $player->getPosition()->getY();
        $sound->z = $player->getPosition()->getZ();
        $sound->volume = $volume;
        $sound->pitch = $pitch;
        $player->getNetworkSession()->sendDataPacket($sound);
    }

}
