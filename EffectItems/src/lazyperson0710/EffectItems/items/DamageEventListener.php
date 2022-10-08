<?php

namespace lazyperson0710\EffectItems\items;

use Deceitya\Flytra\Main;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Durable;
use pocketmine\player\Player;

class DamageEventListener {

    public static function execution(EntityDamageEvent $event, Player $player): void {
        if (Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate()) === true) return;
        if (($event->getCause() === EntityDamageEvent::CAUSE_FALL)) {
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
                    return;
                }
            }
        }
    }
}