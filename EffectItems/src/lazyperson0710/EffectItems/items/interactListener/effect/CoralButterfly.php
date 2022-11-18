<?php

namespace lazyperson0710\EffectItems\items\interactListener\effect;

use lazyperson0710\EffectItems\event\PlayerItemEvent;
use lazyperson710\core\packet\AddEffectPacket;
use lazyperson710\core\packet\SendMessage\SendTip;
use lazyperson710\core\packet\SoundPacket;
use lazyperson710\core\task\IntervalTask;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;

class CoralButterfly {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item): void {
        $event->cancel();
        $player = $event->getPlayer();
        if (PlayerItemEvent::checkInterval($player) === false) return;

        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        if (mt_rand(1, 2) === 1) {
            $effect = new EffectInstance(VanillaEffects::HASTE(), 20 * 60 * 15, 50, false);
            AddEffectPacket::Add($player, $effect, VanillaEffects::HASTE(), true);
        } else {
            $effect = new EffectInstance(VanillaEffects::INSTANT_DAMAGE(), 50, 255, false);
            AddEffectPacket::Add($player, $effect, VanillaEffects::INSTANT_DAMAGE(), true);
        }
        SoundPacket::Send($player, 'item.trident.return');
    }

}
