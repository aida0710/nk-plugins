<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\packet\AddEffectPacket;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemFactory;
use pocketmine\player\GameMode;

class HasteItem {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem(ItemFactory::getInstance()->get(383, 110, 1));
        }
        $effect = new EffectInstance(VanillaEffects::HASTE(), 20 * 60 * 3, 4, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::HASTE(), true);
        SoundPacket::Send($player, 'item.trident.return');
    }
}
