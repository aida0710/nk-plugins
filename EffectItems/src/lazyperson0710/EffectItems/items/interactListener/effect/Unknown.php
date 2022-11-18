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

class Unknown {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item): void {
        $event->cancel();
        $player = $event->getPlayer();
        if (PlayerItemEvent::checkInterval($player) === false) return;

        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::ABSORPTION(), 20 * 60 * 3, 50, false), VanillaEffects::ABSORPTION(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::BLINDNESS(), 20 * 60 * 3, 50, false), VanillaEffects::BLINDNESS(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::CONDUIT_POWER(), 20 * 60 * 3, 50, false), VanillaEffects::CONDUIT_POWER(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::FATAL_POISON(), 20 * 60 * 3, 50, false), VanillaEffects::FATAL_POISON(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 20 * 60 * 3, 50, false), VanillaEffects::FIRE_RESISTANCE(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::HASTE(), 20 * 60 * 3, 50, false), VanillaEffects::HASTE(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::HEALTH_BOOST(), 20 * 60 * 3, 50, false), VanillaEffects::HEALTH_BOOST(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::HUNGER(), 20 * 60 * 3, 50, false), VanillaEffects::HUNGER(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::INSTANT_DAMAGE(), 20 * 60 * 3, 50, false), VanillaEffects::INSTANT_DAMAGE(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::INSTANT_HEALTH(), 20 * 60 * 3, 50, false), VanillaEffects::INSTANT_HEALTH(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::INVISIBILITY(), 20 * 60 * 3, 50, false), VanillaEffects::INVISIBILITY(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::JUMP_BOOST(), 20 * 60 * 3, 50, false), VanillaEffects::JUMP_BOOST(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::LEVITATION(), 20 * 60 * 3, 50, false), VanillaEffects::LEVITATION(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::MINING_FATIGUE(), 20 * 60 * 3, 50, false), VanillaEffects::MINING_FATIGUE(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::NAUSEA(), 20 * 60 * 3, 50, false), VanillaEffects::NAUSEA(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::NIGHT_VISION(), 20 * 60 * 3, 50, false), VanillaEffects::NIGHT_VISION(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::POISON(), 20 * 60 * 3, 50, false), VanillaEffects::POISON(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::REGENERATION(), 20 * 60 * 3, 50, false), VanillaEffects::REGENERATION(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::RESISTANCE(), 20 * 60 * 3, 50, false), VanillaEffects::RESISTANCE(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::SATURATION(), 20 * 60 * 3, 50, false), VanillaEffects::SATURATION(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::SLOWNESS(), 20 * 60 * 3, 50, false), VanillaEffects::SLOWNESS(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::SPEED(), 20 * 60 * 3, 50, false), VanillaEffects::SPEED(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::STRENGTH(), 20 * 60 * 3, 50, false), VanillaEffects::STRENGTH(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::WATER_BREATHING(), 20 * 60 * 3, 50, false), VanillaEffects::WATER_BREATHING(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::WEAKNESS(), 20 * 60 * 3, 50, false), VanillaEffects::WEAKNESS(), true);
        AddEffectPacket::Add($player, new EffectInstance(VanillaEffects::WITHER(), 20 * 60 * 3, 50, false), VanillaEffects::WITHER(), true);
        SoundPacket::Send($player, 'item.trident.return');
    }
}
