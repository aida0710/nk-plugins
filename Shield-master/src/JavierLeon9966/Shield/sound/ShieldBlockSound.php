<?php

declare(strict_types = 1);

namespace JavierLeon9966\Shield\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\world\sound\Sound;

class ShieldBlockSound implements Sound {

    public function encode(Vector3 $pos) : array {
        return [LevelSoundEventPacket::nonActorSound(LevelSoundEvent::ITEM_SHIELD_BLOCK, $pos, false)];
    }
}
