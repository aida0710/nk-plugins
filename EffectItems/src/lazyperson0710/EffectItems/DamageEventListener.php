<?php

namespace lazyperson0710\EffectItems;

use pocketmine\entity\object\ItemEntity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\item\Durable;
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
            if (!$event->getEntity() instanceof Player) return;
            $playerName = $event->getEntity()->getName();
            try {
                if ($player = Server::getInstance()->getPlayerExact($playerName)) {
                    $armorInventory = $player->getArmorInventory();
                    for ($i = 0, $size = $armorInventory->getSize(); $i < $size; ++$i) {
                        $item = clone $armorInventory->getItem($i);
                        if ($item->getNamedTag()->getTag('DefensiveStone') !== null) {//DefensiveStone
                            if ($item instanceof Durable) {
                                $damage = $event->getBaseDamage() * 2;
                                $item->applyDamage($damage);
                                $armorInventory->setItem($i, $item);
                                $player->sendTip("§bDurable §7>> §a落下ダメージが無効化されました！耐久 -{$damage}");
                                $event->cancel();
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                var_dump($exception);
                return;
            }
            return;
        }
    }
}