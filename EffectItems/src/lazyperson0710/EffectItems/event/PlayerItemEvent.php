<?php

declare(strict_types = 0);

namespace lazyperson0710\EffectItems\event;

use lazyperson0710\EffectItems\items\interactListener\AirBlock;
use lazyperson0710\EffectItems\items\interactListener\CommandStorage;
use lazyperson0710\EffectItems\items\interactListener\CompressionLevelItem;
use lazyperson0710\EffectItems\items\interactListener\effect\Churu;
use lazyperson0710\EffectItems\items\interactListener\effect\CoralButterfly;
use lazyperson0710\EffectItems\items\interactListener\effect\CreamyPotato;
use lazyperson0710\EffectItems\items\interactListener\effect\HasteItem;
use lazyperson0710\EffectItems\items\interactListener\effect\HeavenGrass;
use lazyperson0710\EffectItems\items\interactListener\effect\LightMushroom;
use lazyperson0710\EffectItems\items\interactListener\effect\RedBull;
use lazyperson0710\EffectItems\items\interactListener\effect\Unknown;
use lazyperson0710\EffectItems\items\interactListener\effect\ZONe;
use lazyperson0710\EffectItems\items\interactListener\EffectCleaner;
use lazyperson0710\EffectItems\items\interactListener\LoginBonusItem;
use lazyperson0710\EffectItems\items\interactListener\LuckyExpCoin;
use lazyperson0710\EffectItems\items\interactListener\LuckyMoneyCoin;
use lazyperson0710\EffectItems\items\interactListener\LuckyTreeCoin;
use lazyperson0710\EffectItems\items\interactListener\PlayersGetLocation;
use lazyperson710\core\packet\SendMessage\SendTip;
use lazyperson710\core\task\IntervalTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\player\Player;

class PlayerItemEvent implements Listener {

    public static function checkInterval(Player $player) : bool {
        if (IntervalTask::check($player, 'EffectItems')) {
            SendTip::Send($player, '現在エフェクトアイテムのインターバル中です', 'Items', false);
            return false;
        } else {
            IntervalTask::onRun($player, 'EffectItems', 20);
            return true;
        }
    }

    public function onItemUse(PlayerItemUseEvent $event): void {
        if ($event->isCancelled()) return;
        $this->onItemEvents($event);
    }

    public function onItemEvents(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('Compression') !== null) CompressionLevelItem::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('AirBlock') !== null) AirBlock::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('Churu') !== null) Churu::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('CommandStorage') !== null) CommandStorage::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('EffectCleaner') !== null) EffectCleaner::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('HeavenGrass') !== null) HeavenGrass::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('LightMushroom') !== null) LightMushroom::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('LuckyExpCoin') !== null) LuckyExpCoin::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('LuckyMoneyCoin') !== null) LuckyMoneyCoin::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('LuckyTreeCoin') !== null) LuckyTreeCoin::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('PlayersGetLocation') !== null) PlayersGetLocation::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('RedBull') !== null) RedBull::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('UnknownItem') !== null) Unknown::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('ZONe') !== null) ZONe::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('CoralButterfly') !== null) CoralButterfly::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('CreamyPotato') !== null) CreamyPotato::execution($event, $inHand);
        if ($inHand->getNamedTag()->getTag('HasteItem') !== null) HasteItem::execution($event, $inHand);
        if ($inHand->getId() === -199) LoginBonusItem::execution($event);
        if ($inHand->getId() === 383 && $inHand->getMeta() === 110) HasteItem::execution($event, $inHand);
        if ($inHand->getId() === 383 && $inHand->getMeta() === 35) CommandStorage::execution($event, $inHand);
    }

    public function onItemInteract(PlayerInteractEvent $event) {
        if ($event->isCancelled()) return;
        $this->onItemEvents($event);
    }

}
