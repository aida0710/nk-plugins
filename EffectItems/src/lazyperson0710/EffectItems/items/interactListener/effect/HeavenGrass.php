<?php

namespace lazyperson0710\EffectItems\items\interactListener\effect;

use lazyperson0710\EffectItems\Main;
use lazyperson710\core\packet\AddEffectPacket;
use lazyperson710\core\packet\SendMessage\SendTip;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundMessage;
use lazyperson710\core\packet\SoundPacket;
use lazyperson710\core\task\IntervalTask;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;
use pocketmine\scheduler\ClosureTask;

class HeavenGrass {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item): void {
        $event->cancel();
        $player = $event->getPlayer();
        if (IntervalTask::check($player, 'EffectItems')) {
            SendTip::Send($player, '現在エフェクトアイテムのインターバル中です', 'EffectItems', false);
            return;
        } else {
            IntervalTask::onRun($player, 'EffectItems', 20 * 3);
        }
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $effect = new EffectInstance(VanillaEffects::SLOWNESS(), 20 * 5, 5, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::SLOWNESS(), true);
        $effect = new EffectInstance(VanillaEffects::RESISTANCE(), 20 * 120, 3, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::RESISTANCE(), true);
        Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
            function () use ($player): void {
                $effect = new EffectInstance(VanillaEffects::NAUSEA(), 20 * 15, 15, false);
                AddEffectPacket::Add($player, $effect, VanillaEffects::NAUSEA(), true);
                SoundPacket::Send($player, 'item.shield.block');
            }
        ), 20 * 25);
        SoundPacket::Send($player, 'item.trident.return');
    }
}
