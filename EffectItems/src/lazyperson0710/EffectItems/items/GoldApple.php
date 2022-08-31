<?php

namespace lazyperson0710\EffectItems\items;

use Deceitya\MiningLevel\Event\EventListener;
use lazyperson710\core\packet\AddEffectPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class GoldApple {

    public static function init(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->removeItem($item->setCount(1));
        $vanillaEffect = VanillaEffects::HASTE();
        $effect = new EffectInstance($vanillaEffect, 20 * 60, 3, false);
        AddEffectPacket::init($player, $effect, $vanillaEffect, true);
        EventListener::getInstance()->LevelCalculation($player, 500);
    }
}
