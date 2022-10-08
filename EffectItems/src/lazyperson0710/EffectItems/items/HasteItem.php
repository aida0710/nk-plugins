<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson710\core\packet\AddEffectPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemFactory;

class HasteItem {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $player->getInventory()->removeItem(ItemFactory::getInstance()->get(383, 110, 1));
        $effect = new EffectInstance(VanillaEffects::HASTE(), 20 * 60 * 3, 4, false);
        AddEffectPacket::init($player, $effect, VanillaEffects::HASTE(), true);
        $player->sendMessage("§bEffect §7>> §a採掘速度上昇Lv.5を３分間付与しました");
    }
}
