<?php

namespace lazyperson0710\EffectItems\event;

use lazyperson0710\EffectItems\items\DamageEventListener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;

class PlayerDamageEvent implements Listener {

    public function onEntityDamage(EntityDamageEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getEntity();
        if (!$player instanceof Player) return;
        DamageEventListener::execution($event, $player);
        if ($event->getCause() == EntityDamageEvent::CAUSE_BLOCK_EXPLOSION) {
            $player->sendTip("§bExplosion §7>> §a爆発ダメージが無効化されました");
            $event->cancel();
        }
    }
}
