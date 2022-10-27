<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\packet\AddEffectPacket;
use lazyperson710\core\packet\SendNoSoundMessage;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;

class LightMushroom {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item): void {
        $event->cancel();
        $player = $event->getPlayer();
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 20 * 60 * 10, 1, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::NIGHT_VISION(), true);
        if (mt_rand(1, 5) === 1) {
            $effect = new EffectInstance(VanillaEffects::POISON(), 20 * 15, 3, false);
            AddEffectPacket::Add($player, $effect, VanillaEffects::POISON(), true);
            //todo なんかデメリットっぽい音出したいよね
            //SoundPacket::Send($player, 'entity.generic.drown');
            SendNoSoundMessage::Send($player, "キノコの毒に侵されてしまった!", "LightMushroom", false);
        }
        SoundPacket::Send($player, 'item.trident.return');
    }
}