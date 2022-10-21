<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use Deceitya\MiningLevel\Event\EventListener;
use lazyperson710\core\packet\AddEffectPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;

class EnchantedGoldApple {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item): void {
        $event->cancel();
        $player = $event->getPlayer();
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $effect = new EffectInstance(VanillaEffects::HASTE(), 20 * 60 * 5, 14, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::HASTE(), true);
        EventListener::getInstance()->LevelCalculation($player, 5000);
    }
}
