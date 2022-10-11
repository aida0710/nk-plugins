<?php

namespace lazyperson0710\EffectItems\event;

use lazyperson0710\EffectItems\items\breakListener\ExplosionTNT;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class PlayerBlockPlaceEvent implements Listener {

    public function onBlockPlace(BlockPlaceEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $NamedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($NamedTag->getTag('ExplosionBlock') !== null) ExplosionTNT::execution($event);
    }
}
