<?php

namespace lazyperson0710\EffectItems\event;

use lazyperson0710\EffectItems\items\AirBlock;
use lazyperson0710\EffectItems\items\CommandStorage;
use lazyperson0710\EffectItems\items\EffectCleaner;
use lazyperson0710\EffectItems\items\HasteItem;
use lazyperson0710\EffectItems\items\HeavenGrass;
use lazyperson0710\EffectItems\items\PlayersGetLocation;
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
        if ($inHand->getNamedTag()->getTag('AirBlock') !== null) AirBlock::execution($event);
        if ($inHand->getNamedTag()->getTag('HeavenGrass') !== null) HeavenGrass::execution($event);
        if ($inHand->getNamedTag()->getTag('RedBull') !== null) RedBull::execution($event);
        if ($inHand->getNamedTag()->getTag('ZONe') !== null) ZONe::execution($event);
        if ($inHand->getNamedTag()->getTag('UnknownItem') !== null) Unknown::execution($event);
        if ($inHand->getNamedTag()->getTag('EffectCleaner') !== null) EffectCleaner::execution($event);
        if ($inHand->getNamedTag()->getTag('CommandStorage') !== null) CommandStorage::execution($event);
        if ($inHand->getNamedTag()->getTag('PlayersGetLocation') !== null) PlayersGetLocation::execution($event);
        //if ($inHand->getNamedTag()->getTag('ega') !== null) EnchantedGoldApple::init($event);
        //if ($inHand->getNamedTag()->getTag('ga') !== null) GoldApple::init($event);
        if ($inHand->getId() === 383 && $inHand->getMeta() === 110) HasteItem::execution($event);
        if ($inHand->getId() === 383 && $inHand->getMeta() === 35) CommandStorage::execution($event);
    }

}
