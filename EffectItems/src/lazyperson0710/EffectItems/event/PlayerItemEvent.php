<?php

namespace lazyperson0710\EffectItems\event;

use lazyperson0710\EffectItems\items\AirBlock;
use lazyperson0710\EffectItems\items\Churu;
use lazyperson0710\EffectItems\items\CommandStorage;
use lazyperson0710\EffectItems\items\EffectCleaner;
use lazyperson0710\EffectItems\items\HasteItem;
use lazyperson0710\EffectItems\items\HeavenGrass;
use lazyperson0710\EffectItems\items\LoginBonusItem;
use lazyperson0710\EffectItems\items\PlayerTeleportTicket;
use lazyperson0710\EffectItems\items\RedBull;
use lazyperson0710\EffectItems\items\Unknown;
use lazyperson0710\EffectItems\items\ZONe;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class PlayerItemEvent implements Listener {

    public function onItemUse(PlayerItemUseEvent $event) {
        if ($event->isCancelled()) return;
        $this->onItemEvents($event);
    }

    public function onItemInteract(PlayerInteractEvent $event) {
        if ($event->isCancelled()) return;
        $this->onItemEvents($event);
    }

    public function onItemEvents(PlayerItemUseEvent|PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        //todo 使用時のメッセージ表示
        if ($inHand->getNamedTag()->getTag('AirBlock') !== null) AirBlock::init($event);
        if ($inHand->getNamedTag()->getTag('Churu') !== null) Churu::init($event);
        if ($inHand->getNamedTag()->getTag('HeavenGrass') !== null) HeavenGrass::init($event);
        if ($inHand->getNamedTag()->getTag('RedBull') !== null) RedBull::init($event);
        if ($inHand->getNamedTag()->getTag('ZONe') !== null) ZONe::init($event);
        if ($inHand->getNamedTag()->getTag('UnknownItem') !== null) Unknown::init($event);
        if ($inHand->getNamedTag()->getTag('PlayerTeleportTicket') !== null) PlayerTeleportTicket::init($event);
        if ($inHand->getNamedTag()->getTag('EffectCleaner') !== null) EffectCleaner::init($event);
        if ($inHand->getId() === -195) LoginBonusItem::init($event);
        if ($inHand->getId() === 383 && $inHand->getMeta() === 110) HasteItem::init($event);
        if ($inHand->getId() === 383 && $inHand->getMeta() === 35) CommandStorage::init($event);
    }

}
