<?php

namespace lazyperson0710\EffectItems;

use pocketmine\entity\object\ItemEntity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;

class DamageEventListener implements Listener {

    public function onDamage(EntityDamageEvent $event) {
        $item = $event->getEntity();
        //todo 落下ダメージ無効化ブーツ
        //todo 守りの石的な感じで何度かダメージを完全に無効化
        if (!$event->isCancelled()) return;
        if (!$event->getEntity() instanceof ItemEntity) return;
        if (($event->getCause() === EntityDamageEvent::CAUSE_FIRE || $event->getCause() === EntityDamageEvent::CAUSE_LAVA)) {
            /** @var ItemEntity $item */
            if ($item->getItem()->getNamedTag()->getTag('FireResistancePickaxe') !== null) {//FireResistancePickaxe
                $event->cancel();
            }
        }
        if (($event->getCause() === EntityDamageEvent::CAUSE_FALL)) {
            $player = Server::getInstance()->getPlayerExact($event->getEntity()->getTargetEntity()->getNameTag());
            if (!$event->getEntity() instanceof Player) return;
            if (!Server::getInstance()->getPlayerExact($event->getEntity()->getTargetEntity()->getNameTag()) === null) {
                var_dump(1);
                return;
            }
            if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('DefensiveStone') !== null) {//DefensiveStone
                var_dump(0);
                $event->cancel();
            }
        }
    }
}