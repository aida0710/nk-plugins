<?php

declare(strict_types = 0);

namespace lazyperson710\core\packet;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\player\Player;

class AddEffectPacket {

    public static function Add(Player $player, EffectInstance $effect, Effect $vanillaEffects, ?bool $force = false) : void {
        $effectInstance = $player->getEffects()->get($vanillaEffects);
        if ($force === true || $effectInstance === null) {
            $player->getEffects()->add($effect);
        } elseif ($effectInstance->getDuration() < 499) {
            $player->getEffects()->add($effect);
        }
    }

}
