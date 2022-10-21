<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\packet\AddEffectPacket;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;

class HeavenGrass {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item): void {
        $event->cancel();
        $player = $event->getPlayer();
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $effect = new EffectInstance(VanillaEffects::REGENERATION(), 20, 255, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::REGENERATION(), true);
        $effect = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 20 * 60, 1, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::MINING_FATIGUE(), true);
        SoundPacket::Send($player, 'item.trident.return');
    }
}
