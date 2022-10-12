<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\packet\AddEffectPacket;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\player\GameMode;

class Unknown {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $effect = new EffectInstance(VanillaEffects::HASTE(), 20 * 60 * 5, 5, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::HASTE(), true);
        $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 20 * 60 * 5, 1, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::NIGHT_VISION(), true);
        SoundPacket::Send($player, 'item.trident.return');
    }
}
