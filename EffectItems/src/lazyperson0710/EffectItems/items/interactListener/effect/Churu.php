<?php

declare(strict_types = 0);

namespace lazyperson0710\EffectItems\items\interactListener\effect;

use lazyperson0710\EffectItems\event\PlayerItemEvent;
use lazyperson710\core\packet\AddEffectPacket;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\CookedMutton;
use pocketmine\item\Item;
use pocketmine\player\GameMode;

class Churu extends CookedMutton {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item) : void {
        $event->cancel();
        $player = $event->getPlayer();
        if (PlayerItemEvent::checkInterval($player) === false) return;
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $effect = new EffectInstance(VanillaEffects::HEALTH_BOOST(), 20 * 30, 1, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::HEALTH_BOOST(), true);
        $effect = new EffectInstance(VanillaEffects::SPEED(), 20 * 30, 2, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::SPEED(), true);
        SoundPacket::Send($player, 'item.trident.return');
    }
}
