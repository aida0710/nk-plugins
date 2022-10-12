<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\packet\AddEffectPacket;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\player\GameMode;

class RedBull {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $effect = new EffectInstance(VanillaEffects::LEVITATION(), 3, 30, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::LEVITATION(), true);
        SoundPacket::Send($player, 'item.trident.return');
    }

}
